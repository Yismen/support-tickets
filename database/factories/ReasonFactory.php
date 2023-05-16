<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
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
            'priority' => TicketPrioritiesEnum::Normal->value,
            'department_id' => Department::factory(),
            'description' => $this->faker->paragraph(),
        ];
    }

    public function normal()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPrioritiesEnum::Normal->value,
            ];
        });
    }

    public function medium()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPrioritiesEnum::Medium->value,
            ];
        });
    }

    public function high()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPrioritiesEnum::High->value,
            ];
        });
    }

    public function emergency()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPrioritiesEnum::Emergency->value,
            ];
        });
    }
}
