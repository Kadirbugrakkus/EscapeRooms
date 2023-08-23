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
    public function definition()
    {
        $title = $this->faker->randomElement(['Normal', 'Delüx', 'Ultra Delüx']);
        $descriptions = [
            'Normal' => 'Standart bir oda.',
            'Delüx' => 'Geniş ve konforlu bir oda.',
            'Ultra Delüx' => 'Lüks ve üst düzey bir oda.',
        ];

        return [
            'parent_category_id' => null, // Eğer alt kategori olacaksa buraya ilgili kategori ID'si verilir
            'title' => $title,
            'desc' => $descriptions[$title],
            'amount' => $this->faker->randomFloat(2, 100, 1000), // Fiyat aralığını uygun şekilde ayarlayın
        ];
    }
}

