<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    public $payment_types;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$payment_types)
    {
        $this->order = $order;
        $this->payment_types = $payment_types;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* && $this->order->payment_status == 1*/
        $subject = ($this->order->payment_type==1) ? "Có đơn đặt thanh toán online" : "Có đơn đặt thanh toán chuyển khoản hoặc COD";
        $subject = "[" . env('APP_NAME') . "] $subject - #".$this->order->code;
        return $this->view('emails.order.success_admin')
            ->subject($subject);
    }
}