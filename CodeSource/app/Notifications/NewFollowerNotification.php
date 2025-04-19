<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->follower->id,
            'user_name' => $this->follower->name,
            'user_username' => $this->follower->username,
            'user_image' => $this->follower->profile_image,
            'message' => 'a commencé à vous suivre',
            'type' => 'follow',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => $this->follower->id,
            'user_name' => $this->follower->name,
            'user_username' => $this->follower->username,
            'user_image' => $this->follower->profile_image,
            'message' => 'a commencé à vous suivre',
            'type' => 'follow',
            'created_at' => now()->diffForHumans(),
        ]);
    }
}
