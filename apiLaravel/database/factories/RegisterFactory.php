<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'picture' => $this->faker->url()
        ];
    }
}
