<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $supers = [
            [
                'name' => 'Super One',
                'age' => 45,
                'email' => 'super1@example.com',
                'password' => 'superpass1',
            ],
            [
                'name' => 'Super Two',
                'age' => 50,
                'email' => 'super2@example.com',
                'password' => 'superpass2',
            ],
        ];

        foreach ($supers as $super) {
            Admin::create($super + [
                'api_token' => Str::random(60),
                'is_super' => true,
            ]);
        }
    }
}
