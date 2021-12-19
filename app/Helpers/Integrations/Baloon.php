<?php

namespace App\Helpers\Integrations;

use App\Helpers\JWT;
use App\Models\User;
use Faker\Generator;
use App\Models\Broker;
use App\Models\Policy;
use App\Models\Company;
use App\Models\Insurer;
use App\Models\Vehicle;
use Illuminate\Support\Str;

class Baloon
{
    /**
     * The user's email key in the token payload
     *
     * @var string
     */
    const USER_EMAIL_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress';

    /**
     * The user's name key in the token payload
     *
     * @var string
     */
    const USER_NAME_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name';

    const ACCESS_RIGHT_META_KEY = 'baloon_access_rights';

    const BROKER_CODE = 'baloon';

    const BROKER_NAME = 'Baloon';

    const EXTRA_META_FIELDS = [
        'baloon_reseauId' => 'reseauId',
        'baloon_nomReseau' => 'nomReseau',
        'baloon_acteurCommercialId' => 'acteurCommercialId',
        'baloon_nomActeurCommercial' => 'nomActeurCommercial',
    ];

    /**
     * The user's phone key in the token payload
     *
     * @var string
     */
    const USER_MOBILE_KEY = 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/mobilephone';

    /**
     * The name for garage id in the metas table
     *
     * @var string
     */
    const GARAGE_META_KEY = 'baloon_garage_id';

    /**
     * The Baloon customer (dossier contact)
     *
     * @var string
     */
    private static User $customer;

    /**
     * The ids of policies that have been created
     *
     * @var array
     */
    private static array $policyIds = [];

    /**
     * Baloon's Broker instance
     *
     * @var Broker
     */
    private static Broker $brokerInstance;

    /**
     * Create a new user instance for authentication from Baloon SSO.
     *
     * @param  array  $payload - baloon request payload
     * @return \App\Models\User
     */
    public static function createUserForAuth(array $requestPayload)
    {
        $jwtPayload = JWT::decodePayload($requestPayload['baloonSsoInfo']['token']);

        $email = $jwtPayload[static::USER_EMAIL_KEY];
        $name = $jwtPayload[static::USER_NAME_KEY];
        $mobile = $jwtPayload[static::USER_MOBILE_KEY];
        $name = explode(' ', $name);

        $baloon = static::createCompany();

        $user = User::firstOrCreate([
            'email' => $email,
            'type' => 'broker',
        ], [
            'first_name' => $name[0],
            'last_name' => isset($name[1]) ? $name[1] : '',
            'company_id' => $baloon->id,
            'password' => bcrypt('baloon'),
            'mobile' => $mobile,
            'email_verified_at' => now(),
        ]);

        self::saveAccessRights($user,$requestPayload);

        return $user;

    }

    protected static function saveAccessRights(User $user,array $payload){
        if(!empty($payload['baloonSsoInfo']['accessRights'])){
            $accessRightsJson = json_encode($payload['baloonSsoInfo']['accessRights']);
            $user->metas()->firstOrCreate(
                ['name'=>self::ACCESS_RIGHT_META_KEY],
                ['value'=>$accessRightsJson]
            );
        }
    }

    /**
     * Check if the JWT contains valid fields.
     *
     * @param  array  $payload - decoded JWT's payload
     * @return boolean
     */
    public static function hasValidToken(array $payload){
        return isset($payload[static::USER_EMAIL_KEY]) && trim($payload[static::USER_EMAIL_KEY]) != ''
            && isset($payload[static::USER_NAME_KEY]) && trim($payload[static::USER_NAME_KEY]) != '';
    }

    public static function createCompany(array $versionContract = [])
    {
        //for authenticated user from baloon
        if(count($versionContract) == 0){
            return Company::firstOrCreate([
                'name' => 'Baloon',
                'code' => 'baloon',
            ]);
        }

        //for policies
        $company = Company::firstOrCreate([
            'name' => $versionContract['nomCompagnie'],
            'code' => Str::slug($versionContract['nomCompagnie'])
        ]);

        $company->meta()->firstOrCreate([
            'name' => 'baloon_company_id',
            'value' => $versionContract['compagnieId'],
        ]);

        return $company;
    }

    public static function createCustomer(array $dossierContact, Company $company)
    {
        $customer = User::whereRaw(
                'JSON_CONTAINS(JSON_UNQUOTE(meta), ?, ?)',
                [(string)$dossierContact['contactId'], '$.baloonContactId']
            )->whereNotNull('meta')
            ->first();


        if($customer) {
            static::$customer = $customer;
            return $customer;
        }

        $name = explode(' ', $dossierContact['nomContact']);

        $first_name = $name[0];
        $last_name = $name[1];

        $customer = User::create([
            'type' => 'user',
            'company_id' => $company->id,
            'email' => $dossierContact['email'] ?? \strtolower($first_name) ."." . \strtolower($last_name) . "." . Str::random(10) . '@example.com',
            'first_name' => $first_name,
            'last_name' => $last_name,
            'mobile' => $dossierContact['telephone'],
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'meta->baloonContactId' => $dossierContact['contactId'],
        ]);

        static::$customer = $customer;

        return $customer;
    }

    public static function setCustomerBroker(User $broker)
    {
        if(!static::$customer) {
            return;
        }

        static::$customer->update([
            'meta->broker_id' => $broker->id,
        ]);
    }

    public static function createPolicies(array $dossierContact)
    {
        $vehicles = $dossierContact['risques'];

        \collect($dossierContact['versionsContrats'])
            ->each(function($versionContract) use ($dossierContact) {
                \collect($versionContract['risques'])
                    ->filter(fn($risque) => $risque['identifiant'] != '')
                    ->each(function($risque) use ($versionContract, $dossierContact){

                        $risqueDetails = collect($dossierContact['risques'])
                            ->filter(fn($r) => $r['identifiant'] != '')
                            ->firstWhere('identifiant', $risque['identifiant']);

                        if(!$risqueDetails) {
                            throw new \Exception('Version contract risque:'. $risque['identifiant']. ' not found in risques');
                        }

                        $policy = static::getOrCreatePolicy($dossierContact, $versionContract);

                        static::createVehicle($policy->id, $risqueDetails);

                        static::$policyIds[] = $policy->id;
                    });
        });
    }

    private static function getOrCreatePolicy(array $dossierContact, array $versionContract)
    {
        $company = static::createCompany($versionContract); //remove

        $insurer = self::createInsurer($versionContract);
        $broker = self::getBrokerModel();

        $customer = static::createCustomer($dossierContact, $company);

        $policy = Policy::firstOrCreate([
            'number' => $versionContract['contratId'],
            'user_id' => $customer->id,
        ],[
            'broker_id' => $broker->id,
            'insurer_id' => $insurer->id,
            'company_id' => $company->id, //remove
            'type' => 'comprehensive', //remove or make optional
            'expires_at' => isset($versionContract['fin']) ? $versionContract['fin'] : null,
            'created_at' => $versionContract['debut'],
        ]);

        foreach(self::EXTRA_META_FIELDS as $metaKey => $baloonKey)
        {
            $policy->metas()->firstOrCreate([
                'name' => $metaKey,
                'value' => $versionContract[$baloonKey],
            ]);
        }

        return $policy;
    }

    private static function createVehicle(int $policyId, array $risque)
    {
        $faker = app(Generator::class);
        $designations = explode(' ', $risque['designation']);
        $model = count($designations) > 3 ? $designations[1] ." ". $designations[2] : $designations[1];

        Vehicle::firstOrCreate([
            'policy_id' => $policyId,
            'registration_number' => $risque['identifiant'],
        ], [
            'engine_number' => str_shuffle(rand(100000, 999999999) . rand(100000, 999999999)),
            'chassis_number' => str_shuffle(rand(100000, 999999999) . rand(100000, 999999999)),
            'manufacturer' => $designations[0],
            'model' => $model,
            'year' => rand(1990, 2021),
            'color' => $faker->colorName(),
            'gear_type' => $faker->randomElement(['manual', 'auto']),
            'estimate' => rand(100000, 999999999),
        ]);
    }

    public static function getPolicyIds()
    {
        return static::$policyIds;
    }

    public static function getBrokerModel():Broker{

        return self::$brokerInstance = self::$brokerInstance ?? Broker::firstOrCreate(
            ['code' => self::BROKER_CODE],
            ['name' => self::BROKER_NAME]
        );
    }

    public static function getInsurerIdsForCompagnies(array $compagnies){
       return self::getBrokerModel()
            ->insurers()
            ->wherePivotIn('insurer_id_from_broker',$compagnies)
            ->get(['insurers.id'])->pluck('id')
            ->toArray();
    }

    public static function createInsurer(array $versionContract){
        $broker = self::getBrokerModel();

        $insurer = $broker->insurers()
            ->wherePivot('insurer_id_from_broker', $versionContract['compagnieId'])
            ->first();

        if($insurer) {
            return $insurer;
        }

        $insurer = $broker->insurers()->create([
            'name' => $versionContract['nomCompagnie'],
        ]);

        $insurer->brokers()->updateExistingPivot($broker->id, [
            'insurer_id_from_broker' => $versionContract['compagnieId']
        ]);

        return $insurer;
    }
}
