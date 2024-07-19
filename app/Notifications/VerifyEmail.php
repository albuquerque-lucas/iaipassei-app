<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class VerifyEmail extends Notification
{
    use Queueable;

    protected $newEmail;
    protected $userId;

    public function __construct($newEmail, $userId)
    {
        $this->newEmail = $newEmail;
        $this->userId = $userId;
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify.new.email',
            Carbon::now()->addMinutes(60),
            [
                'id' => $this->userId,
                'email' => $this->newEmail,
            ]
        );
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirmação de Mudança de E-mail')
            ->line('Você solicitou a mudança de e-mail para este endereço. Por favor, clique no link abaixo para confirmar a alteração:')
            ->action('Confirmar mudança de e-mail', $this->verificationUrl($notifiable))
            ->line('Se você não solicitou esta mudança, por favor ignore este e-mail.');
    }
}
