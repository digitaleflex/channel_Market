<?php

namespace App\Mail;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminActivityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $log;

    /**
     * Create a new message instance.
     */
    public function __construct(ActivityLog $log)
    {
        $this->log = $log;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $emoji = match ($this->log->action) {
            'product_created' => '🆕',
            'product_updated' => '🔄',
            'product_deleted' => '❌',
            'setting_updated' => '⚙️',
            'order_success' => '💰',
            default => '🔔',
        };

        return new Envelope(
            subject: "{$emoji} NOTIFICATION ADMIN : ".$this->log->description,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin-activity-alert',
        );
    }
}
