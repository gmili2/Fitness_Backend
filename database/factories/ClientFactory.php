<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'age' => $this->faker->numberBetween(16, 80),
            'image_path' => null, // ou une image factice si tu veux
            'phone_number' => $this->faker->phoneNumber(),
            'registration_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'expiration_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'user_id' => User::factory(),
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
