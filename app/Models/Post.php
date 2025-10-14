<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // ← ligne à ajouter
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;    

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','title','slug','excerpt','featured_image','content','status','published_at'
    ];

    protected $casts = ['published_at' => 'datetime'];

    public function author() { return $this->belongsTo(User::class, 'user_id'); }
    public function categories() { return $this->belongsToMany(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class); }
    public function comments() { return $this->hasMany(Comment::class)->latest(); }

    // Génération auto du slug si vide
    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $base = Str::slug($post->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug',$slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $post->slug = $slug;
            }
        });
    }

    // Articles précédent/suivant (publiés)
    public function previous()
    {
        return static::where('status', 'published')
            ->where('published_at', '<', $this->published_at)
            ->orderBy('published_at', 'desc')->first();
    }
    public function next()
    {
        return static::where('status', 'published')
            ->where('published_at', '>', $this->published_at)
            ->orderBy('published_at', 'asc')->first();
    }
}

