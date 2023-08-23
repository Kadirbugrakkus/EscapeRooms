<?php

namespace Database\Factories;

use App\Models\RoomCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $cachedDefinition;

    public function definition()
    {
        if (!$this->cachedDefinition) {
            $title = $this->faker->randomElement(['Normal', 'Delüx', 'Ultra Delüx']);
            $descriptions = [
                'Normal' => 'Standart bir oda.',
                'Delüx' => 'Geniş ve konforlu bir oda.',
                'Ultra Delüx' => 'Lüks ve üst düzey bir oda.',
            ];

            $this->cachedDefinition = [
                'title' => $title,
                'desc' => $descriptions[$title],
                'amount' => $this->faker->randomFloat(2, 100, 1000),
            ];
        }

        return $this->cachedDefinition;
    }
}

