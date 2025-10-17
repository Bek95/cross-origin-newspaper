<?php

namespace Database\Seeders;

use App\Domain\Press\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Principal',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}
