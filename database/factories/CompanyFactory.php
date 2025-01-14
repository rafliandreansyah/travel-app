<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'travel_name' => fake()->company() . ' Travel',
            'province' => 'jawa timur',
            'city' => fake()->city(),
            'address' => fake()->address(),
            'postal_code' => fake()->randomNumber(6, true),
            'phone_number' => fake()->unique()->phoneNumber(),
            'created_at' => fake()->dateTimeBetween(
                now()->subYear()->startOfYear()->toDateString(),  // Awal tahun kemarin
                now()->subYear()->endOfYear()->toDateString()
            ),
            'active' => true,
        ];
    }
}
