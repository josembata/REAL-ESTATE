<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OwnershipRejectedNotification extends Notification 
{
    

    protected $propertyName;
    protected $remarks;

    public function __construct($propertyName, $remarks = null)
    {
        $this->propertyName = $propertyName;
        $this->remarks = $remarks;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Property Ownership Rejected')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your ownership request for the property "' . $this->propertyName . '" has been rejected.');

        if ($this->remarks) {
            $mail->line('Reason: ' . $this->remarks);
        }

        $mail->line('Please review your submission or contact support for assistance.');

        return $mail;
    }
}
