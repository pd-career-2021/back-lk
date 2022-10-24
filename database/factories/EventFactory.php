<?php

namespace Database\Factories;

use App\Models\Audience;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(random_int(2, 5), true)),
            'desc' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'audience_id' => Audience::inRandomOrder()->first()->id,
        ];
    }
}
