<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->text,
            'due_date' => $this->faker->dateTime,
            'is_done' => $this->faker->boolean,
        ];
    }
}
