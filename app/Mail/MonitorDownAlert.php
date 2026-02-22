<?php

namespace App\Mail;

use App\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MonitorDownAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Monitor $monitor,
        public int $packetLoss,
        public float $latency
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⚠️ Alert: {$this->monitor->name} is experiencing high packet loss ({$this->packetLoss}%)",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.monitor-down-alert',
        );
    }
}
