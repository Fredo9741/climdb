<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Site> */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'nom' => 'Site '.$this->faker->city,
            'adresse' => $this->faker->streetAddress,
            'ville' => $this->faker->city,
            'code_postal' => $this->faker->postcode,
            'pays' => $this->faker->countryCode,
            'client_id' => Client::factory(),
        ];
    }
} 