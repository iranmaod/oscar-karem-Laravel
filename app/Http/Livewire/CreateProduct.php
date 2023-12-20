<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\CommissionPaymentType;
use App\Models\CommissionEmployeeType;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;

class CreateProduct extends Component
{
    public $product;
    public $productId;
    public $action;
    public $button;
    public $percentages;
    public $paymentTypes;
    public $employeeTypes;
    protected $hubspot;
    public $elopageProductId;

    protected function getRules()
    {
        $rules = ($this->action == "updateProduct") ? [


        ] : [

        ];

        return array_merge([
          'product.elopage_product_id' => 'required',
          'product.name' => 'required',
          'product.description' => '',
          'product.status_id' => 'required',
        ], $rules);
    }

    public function createProduct()
    {
        $this->resetErrorBag();
        $this->validate();


        Product::create($this->product);

        $this->emit('saved');
        $this->reset('product');
    }




    public function updateProduct()
    {
        $this->resetErrorBag();
        $this->validate();

        Product::query()
            ->where('id', base64_decode($this->productId))
            ->update([
                "elopage_product_id" => $this->product['elopage_product_id'],
                "name" => $this->product['name'],
                "description" => $this->product['description'],
                "status_id" => $this->product['status_id'],
            ]);

        $this->emit('saved');
    }



    public function mount ()
    {

        if (!$this->product && $this->productId) {
            $productId = base64_decode($this->productId);
            $this->product = Product::find($productId);
        }

        if($this->elopageProductId){
          $elopageProductId = base64_decode($this->elopageProductId);
          $elopage_api_key =  base64_decode(env('ELOPAGE_API_KEY'));
          $elopage_api_secret = base64_decode(env('ELOPAGE_API_SECRET'));
          $elopage_response = Http::withHeaders([
                'Content-Type' => 'application/json'
          ])->get("https://api.elopage.com/api/products/".$elopageProductId."?key=".$elopage_api_key."&secret=".$elopage_api_secret);
          $elopageProductsArr = $elopage_response->json();




           if($elopageProductsArr){
             $this->product = array(
               'elopage_product_id' => $elopageProductId,
               'name' => $elopageProductsArr['name'],
             );
           }




        }

        $this->statuses = Status::pluck('id', 'name')->toArray();
        $this->button = create_button($this->action, "Product");
    }



    public function render()
    {
        return view('livewire.create-product');
    }
}
