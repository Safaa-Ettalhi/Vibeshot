<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'image_path',
        'original_post_id',
        'is_hidden',
    ];
    protected $casts = [
        'is_hidden' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function images()
    {
        return $this->hasMany(PostImage::class)->orderBy('order');
    }
    
    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }
    
    public function shares()
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }

    public function isLikedBy($user)
    {
        return $this->likes->contains('user_id', $user->id);
    }

    public function isBookmarkedBy($user)
    {
        return $this->bookmarks->contains('user_id', $user->id);
    }
    
    public function isSharedBy($user)
    {
        return $this->shares->contains('user_id', $user->id);
    }
    
  
    public function isMainImageUsedByOtherPosts()
    {
        return static::where('image_path', $this->image_path)
            ->where('id', '!=', $this->id)
            ->exists();
    }
}
