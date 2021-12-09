<?php

namespace Tests\Feature\BaloonIntegration;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaloonIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public Company $baloon;

    protected function setUp() : void
    {
        parent::setUp();

        $this->baloon = Company::firstOrCreate([
            'name' => 'Baloon',
            'code' => 'baloon',
        ]);;
    }

    
    public function test_baloon_endpoint_for_creating_claim()
    {
        $data = file_get_contents(__DIR__.'/data/createClaimPayload.json');
        $data = json_decode("$data", true);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', 'api/integrations/baloon/create-claim', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'company_id' => $this->baloon->id,
        ]);

        $response->assertJson([
            'data' => [
                'url' => config('app.front') . "customers/1/claims/create",
            ],
        ]);
    }

    public function test_baloon_endpoint_for_listing_claims()
    {
        $data = file_get_contents(__DIR__.'/data/createClaimPayload.json');
        $data = json_decode("$data", true);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', 'api/integrations/baloon/list-claims', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'company_id' => $this->baloon->id,
        ]);

        $response->assertJson([
            'data' => [
                'url' => config('app.front') . "claims",
            ],
        ]);
    }

    /**
     * @dataProvider urlProvider
     */
    public function test_sso_token_is_valid(string $url){
        $data = file_get_contents(__DIR__.'/data/invalidData.json');
        $data = json_decode("$data", true);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', $url, $data);

        $response->assertStatus(422);

        $response->assertJson([
            "error" => [
                "ssoInfoToken" => ["The token in the SSO contains invalid user keys or data."],
            ]
        ]);
    }

    public function urlProvider()
    {
        return [
            'list claims url' => ['api/integrations/baloon/list-claims'],
            'create claims' => ['api/integrations/baloon/create-claim'],
        ];
    }
}
