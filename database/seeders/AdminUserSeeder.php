<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'personnn',
                'email' => 'personnn@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$10$1Wqx7pv2PZVvI/FcF6OIc.X9EMpIwCdU9DVHOEOKCN095Oer0nSEC',
                'remember_token' => 'qZa43Y7euKD2eaxIA88ygjRXcNI93ZcX12heAWnqAc8rNeS5hZcFbvIQZE6y',
                'created_at' => '2026-06-26 13:12:49',
                'updated_at' => '2026-06-27 07:14:20',
                'company_id' => null,
                'role' => 'admin',
            ],
            
          
        ]);
    }
}
