<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'personnn@gmail.com',
            ],
            [
                'name' => 'personnn',
                'password' => Hash::make('Admin123!'),
                'company_id' => null,
                'role' => 'admin',
            ]
        );
    }
}