<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswd extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $adress;
    public $passwd;
    public $pathToImage;
    public $year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Adress $adress, $passwd)
    {
        $this->user = $user;
        $this->adress = $adress;
        $this->passwd = $passwd;
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
            subject: 'Nueva contraseña - Peña Ilusión',
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
            view: 'mail.ResetPasswdEmail',
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
