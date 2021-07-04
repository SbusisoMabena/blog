<?php

namespace Database\Factories;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug'=>$this->faker->unique()->slug(),
            'title'=>$this->faker->catchPhrase,
            'content'=>$this->faker->paragraph(),
            'created_at'=>now(),
            'updated_at'=>now()
        ];
    }
}
