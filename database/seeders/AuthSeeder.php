<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = ['username' => 'admin', 'password' => '12345678', 'email' => 'wesam.alabdeh11@gmail.com', 'phone' => '0947592690'];
        $user = User::where('username', 'admin')->first();
        if (! $user) {
            $user = User::create($userData);
        }
        $user->abilities()->sync(Ability::pluck('name')->toArray());

        $regularRole = Role::where('name', 'Regular User')->first();
        if ($regularRole) {
            $regularUserData = [
                'username' => 'user',
                'password' => '12345678',
                'email' => 'regular@example.com',
                'phone' => '0940000000',
            ];

            $regularUser = User::where('username', 'user')->first();
            if (! $regularUser) {
                $regularUser = User::create($regularUserData);
            }

            $regularAbilityNames = $regularRole->abilities()->pluck('name')->toArray();
            $regularUser->abilities()->sync($regularAbilityNames);
        }
    }
}
