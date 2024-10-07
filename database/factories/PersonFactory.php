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
        // has either a phone number, email, or both
        $phone = $this->faker->boolean(50) ? $this->faker->phoneNumber : null;
        $email = $this->faker->boolean(50) ? $this->faker->unique()->safeEmail : null;

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $email,
            'phone' => $phone,
        ];
    }
}
