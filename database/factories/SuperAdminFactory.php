<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\SuperAdmin;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuperAdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SuperAdmin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => UserFactory::new()->create(),
        ];
    }
}
