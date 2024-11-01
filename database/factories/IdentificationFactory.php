<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Identification>
 */
class IdentificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // is document if type contains 'document'
        $randomType = Arr::random(array_keys(config('constants.identification.types')));
        $randomDocument = Arr::random(array_keys(config('constants.identification.document_types')));
        $isDocument = str_contains($randomType, 'document');

        return [
            'type' => $randomType,
            // 'document_type' => $isDocument ? $randomDocument : null,
            'document_name' => $isDocument ? config('constants.identification.document_types.'.$randomDocument) : null,
            'document_number' => $isDocument ? Str::upper($this->faker->bothify('??########')) : null,
            'issue_date' => $isDocument ? $this->faker->date('m/d/Y') : null,
            'expiration_date' => $isDocument ? $this->faker->date('m/d/Y') : null,
        ];
    }
}
