<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Catégories
        $categoryNames = [
            'Tech', 'Design', 'Business', 'Lifestyle', 'Voyage', 'Cuisine', 'Sport', 'Culture'
        ];

        $categories = collect($categoryNames)->map(function ($name) {
            return Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        });

        // Tags
        $tagNames = ['Laravel','PHP','Web','Startup','IA','Sécurité','UX','Cloud','API','Mobile'];
        $tags = collect($tagNames)->map(function ($name) {
            return Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        });

        // Admin + un utilisateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'Alice', 'password' => Hash::make('password'), 'role' => 'user']
        );

        // Préférences par défaut pour l'utilisateur de démonstration
        $user->preferredCategories()->sync($categories->random(3)->pluck('id'));

        // 20 Posts publiés
        Post::factory(20)->create()->each(function ($post) use ($categories, $tags) {
            // 1–3 catégories / 0–4 tags par article
            $post->categories()->sync($categories->random(rand(1,3))->pluck('id'));
            if ($tags->count()) {
                $post->tags()->sync($tags->random(rand(0,4))->pluck('id'));
            }
        });
    }
}
