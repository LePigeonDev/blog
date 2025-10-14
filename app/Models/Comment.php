<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // ← ligne à ajouter
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id','user_id','body','status'];

    public function post() { return $this->belongsTo(Post::class); }
    public function user() { return $this->belongsTo(User::class); }
}

