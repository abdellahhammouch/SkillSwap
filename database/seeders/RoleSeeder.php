<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => RoleEnum::ADMIN->value,
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::STUDENT->value,
            'guard_name' => 'web',
        ]);
    }
}
