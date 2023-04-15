<?php

namespace Dainsys\Support\Database\Factories;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Models\Ticket;
use Orchestra\Testbench\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => UserFactory::new()->create(),
            'ticket_id' => Ticket::factory()->create(),
            'content' => $this->faker->paragraph()
        ];
    }
}
