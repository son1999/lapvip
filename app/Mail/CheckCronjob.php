<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckCronjob extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $err, $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $data, $err)
    {
        //
        $this->name = $name;
        $this->data = $data;
        $this->err = $err;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cronjob')
            ->subject(env('APP_NAME') . " – Cronjob: ".$this->name.' '.\Lib::dateFormat(time(), 'd/m H:i'));
    }
}
