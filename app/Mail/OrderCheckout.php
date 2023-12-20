<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCheckout extends Mailable
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
        $order = Order::where('id', $orderId )->firstOrFail();
    //  dd($order);
        return $this->subject('Auftragsbestätigung '.$orderId.' - BlackWood GmbH')->markdown('emails.orders.checkout', compact('orderId', 'order'))->attach(public_path('/pdf/anlage_a_widerrufserklärung.pdf'), [
            'as' => 'pre-anlage_a_widerrufserklärung.pdf',
            'mime' => 'application/pdf'
        ]);
    }
}
