<?php

namespace App\Mail;

use App\Models\Curriculo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CurriculoRecebido extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Curriculo $curriculo
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Currículo Recebido - '.$this->curriculo->nome,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.curriculo-recebido',
            with: [
                'curriculo' => $this->curriculo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
