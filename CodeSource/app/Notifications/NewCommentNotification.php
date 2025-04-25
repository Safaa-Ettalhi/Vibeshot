<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $comment;

   
    public function __construct(Post $post, Comment $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
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
            'comment_id' => $this->comment->id,
            'message' => 'commented on your post',
            'type' => 'comment',
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
            'comment_id' => $this->comment->id,
            'message' => 'commented on your post',
            'type' => 'comment',
            'created_at' => now()->diffForHumans(),
        ]);
    }
}