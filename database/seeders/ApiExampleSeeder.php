<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiExampleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'apiuser@example.com'],
            [
                'name' => 'API User',
                'password' => Hash::make('password'),
                'is_super' => false,
            ]
        );

        $token = JWTAuth::fromUser($admin);
        $admin->api_token = $token;
        $admin->save();

        $this->command->info('Example JWT Token for apiuser@example.com: ' . $token);
    }
}

