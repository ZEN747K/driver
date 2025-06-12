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
                'email' => 'super1@example.com',
                'password' => 'superpass1',
            ],
            [
                'name' => 'Super Two',
                'email' => 'super2@example.com',
                'password' => 'superpass2',
            ],
        ];

        foreach ($supers as $super) {
            Admin::firstOrCreate(
                ['email' => $super['email']],
                $super + [
                    'api_token' => Str::random(60),
                    'is_super' => true,
                ]
            );
        }
    }
}
