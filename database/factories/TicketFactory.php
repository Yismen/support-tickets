<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
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
            'status' => TicketStatusesEnum::Pending->value,
            'expected_at' => now(),
            'priority' => TicketPrioritiesEnum::Normal->value,
            'completed_at' => now(),
        ];
    }

    public function unassigned()
    {
        return $this->state(function (array $aatributes) {
            return [
                'assigned_to' => null,
                'status' => TicketStatusesEnum::Pending->value,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatusesEnum::InProgress->value,
            ];
        });
    }

    public function onHold()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatusesEnum::OnHold->value,
            ];
        });
    }

    public function completed()
    {
        return $this->state(function (array $aatributes) {
            return [
                'status' => TicketStatusesEnum::Completed->value,
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
