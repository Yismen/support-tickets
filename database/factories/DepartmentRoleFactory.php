<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Models\DepartmentRole;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DepartmentRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => UserFactory::new()->create(),
            'department_id' => Department::factory()->create(),
            'role' => DepartmentRolesEnum::Agent->value,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $aatributes) {
            return [
                'role' => DepartmentRolesEnum::Admin->value
            ];
        });
    }

    public function agent()
    {
        return $this->state(function (array $aatributes) {
            return [
                'role' => DepartmentRolesEnum::Agent->value
            ];
        });
    }
}
