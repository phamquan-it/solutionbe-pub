<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refund>
 */
class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'refund_amount' => $this->faker->randomFloat(2, 10, 1000), // Số tiền hoàn ngẫu nhiên từ 10 đến 1000
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']), // Trạng thái ngẫu nhiên
            'transaction_id' => $this->faker->optional(0)->randomElement(Transaction::pluck('id')->toArray()), // 50% có transaction_id, 50% null
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
