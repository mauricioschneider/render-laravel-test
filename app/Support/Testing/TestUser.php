<?php

namespace App\Support\Testing;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class TestUser implements MustVerifyEmail
{
    use Notifiable;

    public $id;
    public $email;

    public function __construct(int $id, string $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public function hasVerifiedEmail(): bool
    {
        return false;
    }

    public function markEmailAsVerified()
    {
        // Not used in this simulation.
    }

    public function sendEmailVerificationNotification()
    {
        // This method is called by Laravel to send the email.
        // We will override it to simply log the generated link.
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->id, 'hash' => sha1($this->email)]
        );
        logger()->info("Verification URL: " . $verificationUrl);
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }
}