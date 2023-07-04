<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Aws\Ses\SesClient;

class AnswerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data['answer'] === 'accepted') {
            $antwort = 'akzeptiert';
        } elseif ($this->data['answer'] === 'declined') {
            $antwort = 'abgelehnt';
        } else {
            $antwort = 'undefiniert';
        }
        $subject = $this->data['type_of_notification'] . ' wurde ' . $antwort;

        return $this->view('components.emails.notify-updated-request')
            ->subject($subject)
            ->with(['data' => $this->data]);
    }

}
