<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun Dinas Pariwisata Kota Bengkulu')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mereset password akun Anda pada Sistem Informasi Peta Interaktif Wisata Pesisir Kota Bengkulu.')
            ->action('Reset Password', $url)
            ->line('Tautan reset password ini akan kedaluwarsa dalam 60 menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.')
            ->salutation("Hormat kami,\nDinas Pariwisata Kota Bengkulu");
    }
}
