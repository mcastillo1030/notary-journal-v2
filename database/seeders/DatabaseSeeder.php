<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private function addOnePersonAddress(bool $isNew = true)
    {
        $dbAddressCount = \App\Models\Address::count();

        if (! $isNew && $dbAddressCount < 5) {
            $isNew = true;
        }

        if ($isNew) {
            return \App\Models\Address::factory()->make();
        }

        return \App\Models\Address::inRandomOrder()->first();
    }

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
            $id_count = rand(0, 2);

            for ($i = 0; $i < $address_count; $i++) {
                $isNew = (bool) rand(0, 1);

                $address = $person->addresses()->save(
                    $this->addOnePersonAddress($isNew)
                );

                $address->notes()->saveMany(
                    \App\Models\Note::factory()->count($note_count)->make()
                );
            }

            if ($note_count > 0) {
                $person->notes()->saveMany(
                    \App\Models\Note::factory()->count($note_count)->make()
                );
            }

            if ($id_count > 0) {
                $ids = \App\Models\Identification::factory()->count($id_count)->make();
                $person->identifications()->saveMany($ids);
            }
        });

        User::factory()->create([
            'name' => 'Marlon Castillo',
            'email' => 'admin@marloncastillo.dev',
            'password' => bcrypt('jyh.gyt5cnz1PFD8gmq'),
        ]);
    }
}
