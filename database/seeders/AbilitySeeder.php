<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $abilities = [
            'task::index',
            'task::store',
            'task::update',
            'task::delete',
            'task::show',

            'user::index',
            'user::store',
            'user::update',
            'user::delete',
            'user::show',
        ];
        foreach ($abilities as $ability) {
            if (! Ability::where('name', $ability)->exists()) {
                Ability::create(['name' => $ability]);
            }
        }
    }
}
