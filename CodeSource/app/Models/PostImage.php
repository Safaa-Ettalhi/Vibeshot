<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image_path',
        'order'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function isUsedByOtherPosts()
    {
        return static::where('image_path', $this->image_path)
            ->where('id', '!=', $this->id)
            ->exists();
    }
}
