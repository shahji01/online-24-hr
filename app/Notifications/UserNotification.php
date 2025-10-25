<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Messaging;
use Kreait\Laravel\Firebase\Facades\Firebase;

class UserNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $notifiable->fcm_token)
            ->withNotification([
                'title' => $this->title,
                'body' => $this->body,
            ]);

        return $messaging->send($message);
    }
}
