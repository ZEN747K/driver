<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_guests_are_redirected_to_login_when_accessing_drivers(): void
    {
        $response = $this->get('/drivers');

        $response->assertRedirect('/login');
    }

    public function test_driver_store_validation_runs_via_middleware(): void
    {
        $admin = new \App\Models\Admin([
            'id' => 1,
            'name' => 'Test Admin',
            'age' => 30,
            'email' => 'admin@example.com',
            'password' => 'password',
            'api_token' => 'token',
            'is_super' => true,
        ]);
        $this->actingAs($admin, 'admin');

        $response = $this->post('/drivers', []);

        $response->assertSessionHasErrors(['full_name']);
    }
}
