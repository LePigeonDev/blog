<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6, true);
        $slug  = Str::slug($title);

        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $this->faker->paragraph(),
            'featured_image' => "https://picsum.photos/seed/{$slug}/900/450",
            'content' => collect($this->faker->paragraphs(mt_rand(5,9)))
                            ->map(fn($p)=>"<p>{$p}</p>")->implode(''),
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-60 days', 'now'),
        ];
    }
}
