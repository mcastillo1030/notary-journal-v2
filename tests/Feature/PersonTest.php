<?php

use App\Models\Person;

test('people can be instantiated', function () {
    $person = Person::factory()->create();

    expect($person->first_name)->toBeString();
    expect($person->last_name)->toBeString();
    $hasContact = $person->email || $person->phone;
    expect($hasContact)->toBeTrue();
    expect($person->addresses)->toBeInstanceOf('Illuminate\Database\Eloquent\Collection');
});
