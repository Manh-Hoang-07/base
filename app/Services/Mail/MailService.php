<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendOtpMail(string $email, string $subject, string $content): void
    {
        Mail::raw($content, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }
}
