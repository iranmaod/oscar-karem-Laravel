<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Country;
use App\Models\Installment;
use App\Models\ProductAmount;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;

class CreateProductAmount extends Component
{
    public $productAmount;
    public $productAmountId;
    public $action;
    public $button;
    public $percentages;
    public $countries;
    public $installments;

    protected function getRules()
    {
        $rules = ($this->action == "updateProductAmount") ? [


        ] : [

        ];

        return array_merge([
          'productAmount.elopage_product_id' => 'required',
          //'productAmount.country_code' => 'required',
          'productAmount.installment_id' => 'required',
          'productAmount.amount' => 'required',
          //'productAmount.total_amount' => 'required'
        ], $rules);
    }

    public function createProductAmount()
    {
        $this->resetErrorBag();
        $this->validate();


        ProductAmount::create($this->productAmount);

        $this->emit('saved');
        $this->reset('productAmount');
    }




    public function updateProductAmount()
    {
        $this->resetErrorBag();
        $this->validate();

        Product::query()
            ->where('id', base64_decode($this->productAmountId))
            ->update([
              'elopage_product_id' => $this->productAmount['elopage_product_id'],
              'installment_id' => $this->productAmount['installment_id'],
              'amount' => $this->productAmount['amount'],

            ]);

        $this->emit('saved');
    }



    public function mount ()
    {

        if (!$this->productAmount && $this->productAmountId) {
            $productAmountId = base64_decode($this->productAmountId);
            $this->productAmount = Product::find($productAmountId);
        }

        $this->products = Product::orderBy('name', 'asc')->pluck('elopage_product_id', 'name')->toArray();
        //$this->countries = Country::orderBy('offset', 'desc')->orderBy('name_en', 'asc')->pluck('code', 'name_en')->toArray();
        $this->installments = Installment::pluck('id', 'name')->toArray();
        $this->installments["One Time Payment"] = 0;
        $this->statuses = Status::pluck('id', 'name')->toArray();
        $this->button = create_button($this->action, "Product");
    }



    public function render()
    {
        return view('livewire.create-product-amount');
    }
}
