<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // BIGINT unsigned
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // auteur
            $table->string('title');
            $table->string('slug')->unique();       // URL friendly
            $table->text('excerpt')->nullable();    // pour l'accueil (20-40 mots)
            $table->string('featured_image')->nullable();
            $table->longText('content');
            $table->enum('status', ['draft','published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
