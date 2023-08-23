<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'room_id' => function () {
                return Room::factory()->create()->id;
            },
            'reservation_start' => $this->faker->dateTimeBetween('+1 day', '+1 week'),
            'reservation_end' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'other_user_identity' => $this->faker->numberBetween(10000000000, 99999999999),
            'birth_day_discount' => $this->faker->randomFloat(2, 0, 50),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
