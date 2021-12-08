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

    
    public function test_baloon_endpoint_for_claim()
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
}
