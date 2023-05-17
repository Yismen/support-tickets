<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Rating;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Enums\TicketRatingsEnum;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => UserFactory::new(),
            'ticket_id' => Ticket::factory(),
            'rating' => TicketRatingsEnum::MeetsExpectations->value,
            'comment' => $this->faker->paragraph()
        ];
    }
}
