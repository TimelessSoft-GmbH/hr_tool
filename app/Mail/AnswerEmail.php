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
        return $this->view('components.emails.notify-updated-request')
            ->with(['data' => $this->data]);
    }

}
