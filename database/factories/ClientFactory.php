<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Client> */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company,
            'adresse' => $this->faker->streetAddress,
            'ville' => $this->faker->city,
            'code_postal' => $this->faker->postcode,
            'pays' => $this->faker->countryCode,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
} 