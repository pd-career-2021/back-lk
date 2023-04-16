<?php

namespace Database\Factories;

use App\Models\CompanyType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $user->roles()->sync(Role::find(3));

        return [
            'full_name' => ucfirst($this->faker->words(5, true)),
            'short_name' => ucfirst($this->faker->words(3, true)),
            'desc' => $this->faker->paragraph(),
            'user_id' => $user,
            'company_type_id' => CompanyType::inRandomOrder()->first()->id,
        ];
    }
}
