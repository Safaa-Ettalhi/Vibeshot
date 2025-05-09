<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
  
    public function via(object $notifiable): array
    {
        
        return ['database'];
    }


    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'user_username' => auth()->user()->username,
            'user_image' => auth()->user()->profile_image,
            'post_id' => $this->post->id,
            'post_image' => $this->post->image_path,
            'message' => 'liked your post',
            'type' => 'like',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'user_username' => auth()->user()->username,
            'user_image' => auth()->user()->profile_image,
            'post_id' => $this->post->id,
            'post_image' => $this->post->image_path,
            'message' => 'liked your post',
            'type' => 'like',
            'created_at' => now()->diffForHumans(),
        ]);
    }
}