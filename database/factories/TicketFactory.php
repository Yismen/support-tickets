<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Orchestra\Testbench\Factories\UserFactory;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_by' => UserFactory::new()->create(),
            'department_id' => Department::factory(),
            'reason_id' => Reason::factory(),
            'description' => $this->faker->sentence(4),
            // 'assigned_to' => UserFactory::new()->create(),
            // 'assigned_at' => now(),
            // 'expected_at' => now(),
            'priority' => TicketPrioritiesEnum::Normal->value,
            // 'completed_at' => now(),
            'status' => TicketStatusesEnum::Pending->value,
        ];
    }

    public function unassigned()
    {
        return $this->state(function (array $aatributes) {
            return [
                'assigned_to' => null,
                'assigned_at' => null,
            ];
        });
    }

    public function assigned()
    {
        return $this->state(function (array $aatributes) {
            return [
                'assigned_to' => UserFactory::new()->create(),
                'assigned_at' => now(),
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $aatributes) {
            return [
                'assigned_to' => UserFactory::new()->create(),
                'assigned_at' => now(),
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $aatributes) {
            return [
                'completed_at' => now(),
            ];
        });
    }

    public function incompleted()
    {
        return $this->state(function (array $aatributes) {
            return [
                'completed_at' => null,
            ];
        });
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
