<?php

namespace App\Notifications;

use DouglasResende\FCM\Messages\FirebaseMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TipNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast','fcm'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'notify'=>$this->user
        ];
    }

    public function toFcm($notifiable)
    {

        return (new FirebaseMessage())->setMeta([
            'notify'=>$this->user
        ])->setContent('Welcome Im My App',$this->user->name);

    }

    public function broadcastOn()
    {
        return ['notifyChannel'];
    }
}
