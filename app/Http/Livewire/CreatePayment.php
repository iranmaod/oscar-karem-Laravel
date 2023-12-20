<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentState;
use App\Models\Installment;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatePayment extends Component
{
    public $payment;
    public $paymentId;
    public $action;
    public $button;
    public $orders;
    public $paymentMethods;
    public $paymentStates;
    public $installments;
    public $transactions;


    protected function getRules()
    {
        $rules = ($this->action == "updatePayment") ? [

        ] : [

        ];

        return array_merge([
          'payment.order_id' => 'required',
          'payment.payment_state_id' => 'required',
          'payment.payment_method_id' => 'required',
          'payment.installment_id' => 'required',
          'payment.payment_start_count' => 'required',
          'payment.payment_end_count' => 'required'
        ], $rules);
    }

    public function createPayment ()
    {
        $this->resetErrorBag();
        $this->validate();

        Payment::create($this->payment);

        $this->emit('saved');
        $this->reset('payment');
    }

    public function updatePayment ()
    {
        $this->resetErrorBag();
        $this->validate();

        Payment::query()
            ->where('id', $this->paymentId)
            ->update([
                "payment_state_id" => $this->payment->payment_state_id,
                "payment_method_id" => $this->payment->payment_method_id,
                "installment_id" => $this->payment->installment_id,
                "payment_start_count" => $this->payment->payment_start_count,
                "payment_end_count" => $this->payment->payment_end_count,

            ]);

        $this->emit('saved');
    }

    public function mount ()
    {
        if (!$this->payment && $this->paymentId) {
            $this->payment = Payment::find($this->paymentId);
            $this->transactions = Transaction::where('payment_id', $this->paymentId)->get();
        }

        $this->orders = Order::pluck('id', 'id')->toArray();
        $this->payment_methods = PaymentMethod::where('status_id', 3)->pluck('id', 'name')->toArray();
        $this->installments = Installment::where('status_id', 3)->pluck('id', 'name')->toArray();
        $this->payment_states = PaymentState::where('status_id', 3)->pluck('id', 'name')->toArray();


        $this->button = create_button($this->action, "Payment");
    }

    public function render()
    {
        return view('livewire.create-payment');
    }
}
