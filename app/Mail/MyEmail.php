<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Aws\Ses\SesClient;

class MyEmail extends Mailable
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
        return $this->view('components.emails.notify-new-vacation-request')
                    ->with(['data' => $this->data]);
    }

}
