<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'editor']);
        Role::firstOrCreate(['name' => 'reviewer']);

        $user = User::firstOrCreate(
            ['email' => 'admin@fz-crm.local'],
            ['name' => 'Admin', 'password' => Hash::make('ChangeMe123!')]
        );
        $user->syncRoles(['admin']);
    }
}
