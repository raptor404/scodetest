<?php

namespace App\Services;

use App\Mail\SenatorMessage;
use App\Models\Senator;
use Exception;
use Illuminate\Mail\Mailer;


//Adding this extra class to allow us to replace the laravel mailing functions
// all at once in case we switch to a rest api mailing service
class EmailService
{
    public Mailer $mailer;

    public function __construct(Mailer $mail)
    {
        $this->mailer = $mail;
    }


    /**
     * @param Senator $senator
     * @param string $name
     * @param string $from
     * @param string $message
     * @throws Exception
     * @return void
     */
    public function send(Senator $senator, string $name, string $from, string $message){

        $this->mailer
            ->to($senator->email)
            ->send(
                new SenatorMessage($senator, $name, $from, $message)
            );
    }
}
