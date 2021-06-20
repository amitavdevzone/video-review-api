<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'is_active' => 0,
            'user_id' => User::factory()->create()->id,
            'student_count' => 0,
        ];
    }

    public function inActive(): CourseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 0,
            ];
        });
    }

    public function active(): CourseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 1,
            ];
        });
    }
}
