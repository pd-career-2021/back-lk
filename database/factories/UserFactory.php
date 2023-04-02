<?php

namespace Database\Factories;

use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = array_rand(['male', 'female'], 1);

        return [
            'email' => $this->faker->email(),
            'password' => bcrypt($this->faker->password()),
            'name' => $this->faker->firstName($gender == 0 ? 'male' : 'female'),
            'surname' => $this->faker->lastName($gender == 0 ? 'male' : 'female'),
            'sex' => $gender == 0 ? 'male' : 'female',
            'remember_token' => Str::random(10),
            'faculty_id' => Faculty::inRandomOrder()->first()->id,
        ];
    }
}
