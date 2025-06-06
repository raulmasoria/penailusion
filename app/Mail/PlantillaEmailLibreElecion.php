<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlantillaEmailLibreElecion extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $pathToImage;
    public $year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $subject,$body)
    {

        $this->subject = $subject;
        $this->body = $body;
        $this->pathToImage = public_path("/img/Escudo_peña.jpeg");
        $this->year = Carbon::now()->format('Y');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.plantillaEmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
