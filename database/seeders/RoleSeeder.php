<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $regularRole = Role::firstOrCreate(['name' => 'Regular User']);

        $allAbilityNames = Ability::pluck('name')->all();
        $taskAbilityNames = Ability::where('name', 'like', 'task::%')->pluck('name')->all();

        $adminRole->abilities()->sync($allAbilityNames);
        $regularRole->abilities()->sync($taskAbilityNames);
    }
}
