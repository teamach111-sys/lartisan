<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject("Réinitialisation de votre mot de passe — L'Artisan")
            ->greeting('Bonjour !')
            ->line("Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe de votre compte L'Artisan.")
            ->action('Réinitialiser le mot de passe', $url)
            ->line('Ce lien de réinitialisation expirera dans 60 minutes.')
            ->line("Si vous n'avez pas demandé de réinitialisation, aucune action n'est requise.")
            ->salutation("Cordialement,\nL'équipe L'Artisan");
    }
}
