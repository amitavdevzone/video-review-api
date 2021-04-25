<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'url' => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'type' => 'youtube',
            'is_published' => 1,
        ];
    }

    public function unPublished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => 0,
            ];
        });
    }
}
