<?php

namespace App\Http\Livewire;

use App\Models\PaymentMethod;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatePaymentMethod extends Component
{
    public $statuses;
    public $paymentMethod;
    public $paymentMethodId;
    public $action;
    public $button;

    protected function getRules()
    {
        $rules = ($this->action == "updatePaymentMethod") ? [
            'paymentMethod.slug' => 'required|unique:payment_methods,slug,' . $this->paymentMethodId,
            'paymentMethod.status_id' => 'required' // livewire need this
        ] : [
            'paymentMethod.name' => 'required|min:3',
            'paymentMethod.status_id' => 'required' // livewire need this
        ];

        return array_merge([
            'paymentMethod.name' => 'required|min:3',
            'paymentMethod.slug' => 'required|unique:payment_methods,slug'
        ], $rules);
    }

    public function createPaymentMethod()
    {
        $this->resetErrorBag();
        $this->validate();



        PaymentMethod::create($this->paymentMethod);

        $this->emit('saved');
        $this->reset('paymentMethod');
    }

    public function updatePaymentMethod()
    {
        $this->resetErrorBag();
        $this->validate();

        PaymentMethod::query()
            ->where('id', $this->paymentMethodId)
            ->update([
                "name" => $this->paymentMethod->name,
                "slug" => $this->paymentMethod->slug,
                "status_id" => $this->paymentMethod->status_id
            ]);

        $this->emit('saved');
    }

    public function mount ()
    {
        if (!$this->paymentMethod && $this->paymentMethodId) {
            $this->paymentMethod = PaymentMethod::find($this->paymentMethodId);
        }
        $this->statuses = Status::pluck('id', 'name')->toArray();
        $this->button = create_button($this->action, "Payment Method");
    }

    public function render()
    {
        return view('livewire.create-payment-method');
    }
}
