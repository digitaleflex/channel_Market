<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SystemAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;
    public $systemContext;

    public function __construct($errorMessage, $systemContext = [])
    {
        $this->errorMessage = $errorMessage;
        $this->systemContext = $systemContext;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 ALERTE SYSTÈME : ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.system-alert',
        );
    }
}
