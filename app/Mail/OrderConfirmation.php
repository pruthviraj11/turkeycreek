<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    public $amount;
    public $tax;
    public $shippingCharge;
    public $totalAmount;




    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $emailData, $amount, $tax, $shippingCharge, $totalAmount)
    {
        $this->emailData = $emailData;
        $this->amount = $amount;
        $this->tax = $tax;
        $this->shippingCharge = $shippingCharge;
        $this->totalAmount = $totalAmount;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Order Confirmation',
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
            view: 'emails.order_confirmation',
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

    public function build()
    {
        return $this->view('emails.order_confirmation');
    }
}
