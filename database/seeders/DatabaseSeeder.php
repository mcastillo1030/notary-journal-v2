<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Person::factory()
            ->count(50)
            ->hasAddresses(rand(0, 4))
            ->hasNotes(rand(0, 5))
            ->create();

        User::factory()->create([
            'name' => 'Marlon Castillo',
            'email' => 'admin@marloncastillo.dev',
            'password' => bcrypt('jyh.gyt5cnz1PFD8gmq'),
        ]);
    }
}
