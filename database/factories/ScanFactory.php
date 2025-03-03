<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Scan;
use App\Models\Client;

class ScanFactory extends Factory
{
    protected $model = Scan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'barcode' => $this->faker->uuid,
            'date_pointage_sortie' => $this->faker->dateTime,
        ];
    }
}
