<?php

namespace App\Mail;

use App\Models\Senator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SenatorMessage extends Mailable
{
    use Queueable, SerializesModels;

    public Senator $senator;
    public string $name;
    public string $fromAddress;
    public string $messageContent;
    /**
     * Create a new message instance.
     */
    public function __construct(Senator $senator, string $name, string $fromAddress, string $messageContent)
    {
        $this->senator = $senator;
        $this->name = $name;
        $this->fromAddress = $fromAddress;
        $this->messageContent = $messageContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Senator Message',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.senator-contact',
            with: [
                'senator'=> $this->senator,
                'name' => $this->name,
                'from' => $this->fromAddress,
                'messageContent' => $this->messageContent
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
