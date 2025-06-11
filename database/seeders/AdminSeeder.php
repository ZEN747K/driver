<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Alice Example',
                'age' => 30,
                'email' => 'alice@example.com',
                'password' => 'password1',
            ],
            [
                'name' => 'Bob Example',
                'age' => 35,
                'email' => 'bob@example.com',
                'password' => 'password2',
            ],
            [
                'name' => 'Charlie Example',
                'age' => 28,
                'email' => 'charlie@example.com',
                'password' => 'password3',
            ],
            [
                'name' => 'Dana Example',
                'age' => 32,
                'email' => 'dana@example.com',
                'password' => 'password4',
            ],
            [
                'name' => 'Eve Example',
                'age' => 40,
                'email' => 'eve@example.com',
                'password' => 'password5',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin + ['api_token' => Str::random(60)]);
        }
    }
}
