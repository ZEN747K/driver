<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Alice Example',
                'email' => 'alice@example.com',
                'password' => 'password1',
            ],
            [
                'name' => 'Bob Example',
                'email' => 'bob@example.com',
                'password' => 'password2',
            ],
            [
                'name' => 'Charlie Example',
                'email' => 'charlie@example.com',
                'password' => 'password3',
            ],
            [
                'name' => 'Dana Example',
                'email' => 'dana@example.com',
                'password' => 'password4',
            ],
            [
                'name' => 'Eve Example',
                'email' => 'eve@example.com',
                'password' => 'password5',
            ],
        ];

        foreach ($admins as $adminData) {
            /** @var \App\Models\Admin $admin */
            $admin = Admin::firstOrCreate(
                ['email' => $adminData['email']],
                $adminData + ['api_token' => Str::random(60)]
            );

            // Generate example JWT token and store in api_token column
            $admin->api_token = JWTAuth::fromUser($admin);
            $admin->save();
        }
    }
}
