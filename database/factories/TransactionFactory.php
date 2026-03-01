<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'total' => $this->faker->numberBetween(100000, 1000000),
            'method' => $this->faker->randomElement(['CCO','QRIS']),
            'proof_image' => null,
            'status' => 'waiting',
        ];
    }
}