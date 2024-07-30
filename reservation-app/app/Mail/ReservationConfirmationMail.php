<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $date;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param array $date
     */
    public function __construct($name, $date)
    {
        $this->name = $name;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reservation Confirmation Mail')
                    ->view('emails.reservation_confirmation')
                    ->with([
                        'name' => $this->name,
                        'date' => $this->date,
                    ]);
    }
}
