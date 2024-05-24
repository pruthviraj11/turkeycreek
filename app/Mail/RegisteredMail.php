<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('emails.registered_mail')
        //     ->with([
        //         'name' => $this->user->name,
        //         'email' => $this->user->email,
        //     ]);


            return $this->view('emails.registered_mail')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'mobile_no' => $this->user->mobile_no,
                'address' => $this->user->address,
                'zip_code' => $this->user->zip_code,
            ]);
    }
}
