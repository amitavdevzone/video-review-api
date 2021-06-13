<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'entity' => 'video',
            'entity_id' => Video::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
