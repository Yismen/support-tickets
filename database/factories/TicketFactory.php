<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketStatus;
use Dainsys\Support\Enums\TicketPriority;
use Orchestra\Testbench\Factories\UserFactory;
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
            'assigned_to' => UserFactory::new()->create(),
            'assigned_at' => now(),
            'status' => TicketStatus::Pending->value,
            'expected_at' => now(),
            'priority' => TicketPriority::Normal->value,
            'completed_at' => now(),
        ];
    }

    public function unassigned()
    {
        return $this->state(function (array $aatributes) {
            return [
                'assigned_to' => null,
                'status' => TicketStatus::Pending->value,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatus::InProgress->value,
            ];
        });
    }

    public function onHold()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatus::OnHold->value,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatus::Completed->value,
            ];
        });
    }

    public function medium()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPriority::Medium->value,
            ];
        });
    }

    public function high()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPriority::High->value,
            ];
        });
    }

    public function emergency()
    {
        return $this->state(function (array $aatributes) {
            return [
                'priority' => TicketPriority::Emergency->value,
            ];
        });
    }
}
