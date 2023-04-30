<?php

namespace Dainsys\Support\Database\Factories;

use Illuminate\Support\Str;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'ticket_prefix' => Str::random(8),
            'description' => $this->faker->paragraph(),
        ];
    }
}
