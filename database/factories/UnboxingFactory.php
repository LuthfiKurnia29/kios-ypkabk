<?php

namespace Database\Factories;

use App\Models\Unboxing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unboxing>
 */
class UnboxingFactory extends Factory
{
    protected $model = Unboxing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isYoutube = $this->faker->boolean();

        return [
            'title' => 'Unboxing '.$this->faker->words(3, true),
            'url' => $isYoutube
                ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
                : 'https://www.instagram.com/p/C8unboxing'.$this->faker->numberBetween(1, 999).'/',
            'platform' => $isYoutube ? 'youtube' : 'instagram',
            'recorded_at' => $this->faker->date(),
            'is_visible' => true,
            'description' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the unboxing item is hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => false,
        ]);
    }

    /**
     * Indicate that the unboxing item is from YouTube.
     */
    public function youtube(): static
    {
        return $this->state(fn (array $attributes) => [
            'platform' => 'youtube',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    /**
     * Indicate that the unboxing item is from Instagram.
     */
    public function instagram(): static
    {
        return $this->state(fn (array $attributes) => [
            'platform' => 'instagram',
            'url' => 'https://www.instagram.com/reel/C8unboxing123/',
        ]);
    }
}
