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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sesClient = new SesClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        return $this->view('components.emails.myemail')
            ->subject('My Email Subject')
            ->to('recipient@example.com')
            ->from('sender@example.com')
            ->withSwiftMessage(function ($message) use ($sesClient) {
                $message->setBodyCharset('UTF-8');
                $message->setCharset('UTF-8');
                $message->getHeaders()->addTextHeader('X-SES-CONFIGURATION-SET', 'my_configuration_set_name');
                $sesClient->sendRawEmail([
                    'RawMessage' => [
                        'Data' => $message->toString(),
                    ],
                ]);
            });
    }
}
