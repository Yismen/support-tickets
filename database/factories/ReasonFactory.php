<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReasonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reason::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'department_id' => Department::factory(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
