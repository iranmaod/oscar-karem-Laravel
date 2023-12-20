<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payment;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderId = $this->orderId;
  
     
        $transaction = Transaction::where('order_id', $orderId )->first();
        $order = Order::where('id', $orderId )->first();
        // echo "<pre>";
        //  print_r($transaction);exit;

        return $this->subject('Zahlung erfolgreich für Order ID '.$orderId.' ')->markdown('emails.orders.paymentsuccess',compact('order','transaction'));
    }
}