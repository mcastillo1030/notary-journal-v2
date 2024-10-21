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
        $people = Person::factory()
            ->count(50)
            ->create();

        $people->each(function ($person) {
            $address_count = rand(0, 4);
            $note_count = rand(0, 5);

            for ($i = 0; $i < $address_count; $i++) {
                $new = rand(0, 1);

                $address = $person->addresses()->save(
                    $new ? \App\Models\Address::factory()->make() : \App\Models\Address::inRandomOrder()->first()
                );

                $address->notes()->saveMany(
                    \App\Models\Note::factory()->count($note_count)->make()
                );
            }

            $person->notes()->saveMany(
                \App\Models\Note::factory()->count($note_count)->make()
            );
        });

        User::factory()->create([
            'name' => 'Marlon Castillo',
            'email' => 'admin@marloncastillo.dev',
            'password' => bcrypt('jyh.gyt5cnz1PFD8gmq'),
        ]);
    }
}
