<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewCommentNotification extends Notification implements ShouldQueue
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
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
            'user_username' => $this->comment->user->username,
            'user_image' => $this->comment->user->profile_image,
            'post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'message' => 'a commenté votre publication',
            'type' => 'comment',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => $this->comment->user->id,
            'user_name' => $this->comment->user->name,
            'user_username' => $this->comment->user->username,
            'user_image' => $this->comment->user->profile_image,
            'post_id' => $this->post->id,
            'comment_id' => $this->comment->id,
            'comment_content' => $this->comment->content,
            'message' => 'a commenté votre publication',
            'type' => 'comment',
            'created_at' => now()->diffForHumans(),
        ]);
    }
}
