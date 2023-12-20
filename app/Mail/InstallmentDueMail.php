<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Payment;
use App\Models\InstallmentSchedule;
class InstallmentDueMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderId, $installmentId)
    {
        $this->orderId = $orderId;
        $this->installmentId = $installmentId;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderId = $this->orderId;
        $installmentId = $this->installmentId;
      //  dd($installmentId);
        $installment_details = InstallmentSchedule::where('id',$installmentId)->firstOrFail();
        
     
        $order = Order::where('id', $orderId )->firstOrFail();

        return $this->markdown('emails.orders.installmentdue',compact('orderId','order','installmentId','installment_details'));
    }
}
