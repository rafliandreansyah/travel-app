<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Brand::class;

    public function definition(): array
    {
        $dataDate = fake()->dateTimeBetween(
            now()->subYear()->startOfYear()->toDateString(),  // Awal tahun kemarin
            now()->subYear()->endOfYear()->toDateString()
        );
        return [
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'created_at' => $dataDate,
            'updated_at' => $dataDate,
        ];
    }
}
