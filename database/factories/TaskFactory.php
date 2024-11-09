<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $userIds = null;
        static $categoryIds = null;

        if ($userIds === null) {
            $userIds = User::pluck('id')->toArray();
        }

        if ($categoryIds === null) {
            $categoryIds = Category::pluck('id')->toArray();
        }

        return [
            'author_id' => $userIds[array_rand($userIds)],
            'created_by' => $userIds[array_rand($userIds)],
            'deleted_by' => null,
            'category_id' => $categoryIds[array_rand($categoryIds)],
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
        ];
    }
}
