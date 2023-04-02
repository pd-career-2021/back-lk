<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $user->roles()->sync(Role::find(2));

        return [
            'user_id' => $user,
            'desc' => $this->faker->paragraph(),
        ];
    }
}
