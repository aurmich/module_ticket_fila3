<?php

declare(strict_types=1);

namespace Modules\Ticket\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TicketHourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Ticket\Models\TicketHour::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
