<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Resetiranje lozinke')
            ->greeting('Poštovani!')
            ->line('Primili ste ovu poruku jer je zatraženo resetiranje lozinke za Vaš račun.')
            ->action('Resetiraj lozinku', $url)
            ->line('Ovaj link za resetiranje lozinke istječe za 60 minuta.')
            ->line('Ako niste zatražili resetiranje lozinke, možete zanemariti ovu poruku.')
            ->salutation('Srdačan pozdrav!');
    }
}
