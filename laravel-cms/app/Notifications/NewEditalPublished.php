<?php

namespace App\Notifications;

use App\Models\Content;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEditalPublished extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Content $content)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo edital publicado')
            ->line('Um novo edital foi publicado no portal.')
            ->line($this->content->title)
            ->action('Visualizar edital', route('content.show', $this->content->slug));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'content_id' => $this->content->id,
            'title' => $this->content->title,
            'slug' => $this->content->slug,
            'type' => $this->content->type,
        ];
    }
}
