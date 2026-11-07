<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\MovieRequest;

class NewMovieRequestNotification extends Notification
{
    use Queueable;

    public $movieRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(MovieRequest $movieRequest)
    {
        $this->movieRequest = $movieRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Ginagamit ang database para maipakita sa admin dashboard/bell
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('May bagong movie request na isinumite.')
            ->action('Tingnan ang Request', url('/admin/requests'))
            ->line('Salamat sa paggamit ng SAUCEtv!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->movieRequest->id,
            'title' => $this->movieRequest->title,
            'user_id' => $this->movieRequest->user_id,
            'message' => 'Nagsumite ng bagong movie request: ' . $this->movieRequest->title,
        ];
    }
}