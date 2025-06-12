<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Driver;
use Tests\TestCase;

class DataApiTest extends TestCase
{
    public function test_driver_endpoint_requires_authentication(): void
    {
        Driver::factory()->create(['id' => 1]);

        $response = $this->get('/data/api/driver1');

        $response->assertStatus(401);
    }

    public function test_authenticated_access_to_driver_endpoint(): void
    {
        $admin = Admin::factory()->create(['password' => 'secret']);
        Driver::factory()->create(['id' => 1]);

        $loginResponse = $this->post('/data/api/login', [
            'email' => $admin->email,
            'password' => 'secret',
        ], ['Accept' => 'application/json']);

        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        $response = $this->get('/data/api/driver1', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => 1]);
    }
}

