<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OwnershipVerifiedNotification extends Notification 
{
    // use Queueable;

    protected $propertyName;

    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Property Ownership Verified')
           ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your ownership request for the property "' . $this->propertyName . '" has been verified by the administrator.')
            ->line('You now have full access and control over the property in the system.')
            ->line('Thank you for using our platform.');
    }
}
