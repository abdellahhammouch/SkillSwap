<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => 'password',
        ]);

        $admin->assignRole(RoleEnum::ADMIN->value);

        $student = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'first_name' => 'Test',
            'last_name' => 'User',
            'password' => 'password',
        ]);

        $student->assignRole(RoleEnum::STUDENT->value);
    }
}
