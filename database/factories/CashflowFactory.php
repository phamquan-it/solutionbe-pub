<?php

namespace Database\Factories;

use App\Models\Cashflow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cashflow>
 */
class CashflowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Cashflow::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'balance' => $this->faker->randomFloat(2, 1000, 10000), // Random balance between 1K - 10K
            'fluctuation' => $this->faker->randomFloat(2, -500, 500), // Random fluctuation
            'action' => $this->faker->randomElement(['deposit', 'withdrawal']), // Random action
        ];
    }
}
