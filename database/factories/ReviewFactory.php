<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Donatur Preloved', 'Pembeli', 'Orang Tua Siswa']),
            'quote' => fake()->paragraph(),
            'avatar' => null,
            'theme_color' => fake()->randomElement(['sun', 'cream', 'olive']),
            'order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the review is inactive (pending approval).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
