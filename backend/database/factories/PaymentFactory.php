<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => fake()->unique()->numerify('CUST###'),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->unique()->safeEmail(),
            'amount' => fake()->randomFloat(2, 10, 5000), // between 10 and 5000
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'amount_usd' => fake()->randomFloat(2, 10, 5000),
            'reference_no' => fake()->unique()->bothify('REF####??'),
            'date_time' => fake()->dateTimeBetween('-1 year', 'now'),
            'processed' => fake()->boolean(),
        ];
    }
}
