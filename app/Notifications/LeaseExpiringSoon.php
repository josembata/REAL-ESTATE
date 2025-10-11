<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class LeaseExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    public $lease;

    public function __construct(Lease $lease)
    {
        $this->lease = $lease;
    }

    public function via($notifiable)
    {
        return ['mail']; // email + in-app notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lease Expiring Soon')
            ->greeting('Hello ' . $notifiable->name . ',')
            
          ->line('Your lease (' . $this->lease->lease_number . ') will expire on ' 
           . Carbon::parse($this->lease->term_end)->format('d M Y') . '.')
            ->line('Please contact the owner if you wish to renew.')
            ->action('View Lease', url('http://127.0.0.1:8000/leases/'.$this->lease->id))
            ->line('Thank you for using our platform!');
    }

    public function toArray($notifiable)
    {
        return [
            'lease_id' => $this->lease->id,
            'message'  => 'Your lease will expire on ' . $this->lease->term_end->format('d M Y'),
        ];
    }
}
