<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = 0;
        $userCapacity = $this->faker->numberBetween(1, 6);

        return [
            'room_category_id' => RoomCategory::factory(),
            'room_no' => $this->faker->unique()->numberBetween(101, 999),
            'status' => $status,
            'user_capacity' => $userCapacity,
        ];
    }
}

