<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Contact $contact,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[CrePlann] Pesan Baru: '.$this->contact->subject,
            replyTo: [$this->contact->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-notification',
        );
    }
}
