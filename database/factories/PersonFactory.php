<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // has either a phone number or an email address
        $contact = $this->faker->boolean ? 'email' : 'phone';

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            $contact => $contact === 'email' ? $this->faker->unique()->safeEmail : $this->faker->phoneNumber,
        ];
    }
}
