<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplyNotification extends Notification
{
    use Queueable;
    public $title;
    public $reply_user;
    public $ticket_id;

    public function __construct($title, $reply_user, $ticket_id)
    {
        $this->title = $title;
        $this->reply_user = $reply_user;
        $this->ticket_id = $ticket_id;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A new reply has been added to your ticket:')
            ->line('Ticket Title: ' . $this->title)
            ->line('Reply by: ' . $this->reply_user)
            ->action('View Ticket', url('/tickets/' . $this->ticket_id))
            ->line('Thank you for using our application!');
    }
}
