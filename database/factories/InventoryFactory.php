<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->name,
            'vendor_name'  => $this->faker->name,
            'price'        => $this->faker->randomFloat(2,0,1000),
            'most_selling' => $this->faker->numberBetween(1,100),
            'rate'         => $this->faker->numberBetween(1,5),
        ];
    }
}
