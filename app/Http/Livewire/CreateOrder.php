<?php

namespace App\Http\Livewire;

use HubSpot\Factory;
use App\Models\Agent;
use App\Models\Order;
use App\Models\Status;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductAmount;
use Livewire\Component;
use App\Models\Installment;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\VatPercentage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use HubSpot\Client\Crm\Contacts\ApiException;

class CreateOrder extends Component
{
    public $order_statuses;
    public $vat_percentages;
    public $installments;
    public $payment_methods;
    public $commission_types;
    public $installment_frequencies;
    public $payment_types;
    public $genders;
    public $products;
    public $order;
    public $orderId;
    public $contactId;
    public $action;
    public $button;
    public $agents;
    public $isDisabled = false;
    protected $hubspot;
    public $amount = 0;
    public $isManualAmount;


    protected function getRules()
    {
        $rules = ($this->action == "updateOrder") ? [


        ] : [

        ];

        return array_merge([
          'order.hs_vid' => 'required',
          'order.is_manual_amount' => '',
          'order.firstname' => 'required',
          'order.lastname' => 'required',
          'order.email' => 'required',
          'order.phone' => 'required',
          'order.product_id' => '',
          'order.installment_id' => 'required_if:order.payment_type,==,installment',
          'order.address' => 'required',
          'order.amount' => 'required|numeric',
          'order.city' => 'required',
          'order.country_code' => 'required',
          'order.account' => '',
          'order.b_account' => '',
          'order.gender' => '',
          'order.dob' => '',
          'order.plz' => 'required',
          'order.company_name' => '',
          'order.vat' => '',
          'order.vat_percentage_id' => 'required',
          'order.order_status_id' => 'required',
          'order.payment_method_id' => 'required',
          'order.setter_id' => '',
          'order.agent_id' => 'required',
          'order.hs_deal_id' => '',
          'order.sevdesk_invoice_id' => '',
          'order.sevdesk_user_id' => '',
          'order.commission_type' => 'required',
          'order.payment_type' => 'required',
          'order.downpayment_amount' => 'required_if:order.payment_type,==,installment|numeric|lt:order.amount',
          'order.installment_frequency' => 'required_if:order.payment_type,==,installment',
          'order.installment_start_date' => 'required_if:order.payment_type,==,installment',
        ], $rules);
    }

    public function createOrder()
    {
        $this->resetErrorBag();
        $this->validate();

        $this->order['qty'] = 1;
        $this->order['account'] = isset($this->order['account']) && $this->order['account'] ? encrypt_hs_string($this->order['account']) : null;
        $this->order['b_account'] = isset($this->order['b_account']) && $this->order['b_account'] ? encrypt_hs_string($this->order['b_account']) : null;
        $this->order['auth_code'] = generate_random_digit(4);
        $this->order['paid_amount'] = 0;
        $orderData = Order::create($this->order);

        $this->emit('saved');
        $this->reset('order');



        return redirect()->route('order.edit', array('orderId' => base64_encode($orderData->id)));
    }




    public function updateOrder()
    {
        $this->resetErrorBag();
        $this->validate();



        Order::query()
            ->where('id', base64_decode($this->orderId))
            ->update([
                "hs_vid" => $this->order['hs_vid'],
                "firstname" => $this->order['firstname'],
                "lastname" => $this->order['lastname'],
                "email" => $this->order['email'],
                "phone" => $this->order['phone'],
                "product_id" => $this->order['product_id'],
                "installment_id" => $this->order['installment_id'],
                "address" => $this->order['address'],
                "qty" => 1,
                "product_id" => $this->order['product_id'],
                "amount" => $this->order['amount'],
                "city" => $this->order['city'],
                "country_code" => $this->order['country_code'],
                "plz" => $this->order['plz'],
                "company_name" => $this->order['company_name'],
                "vat" => $this->order['vat'],
                "vat_percentage_id" => $this->order['vat_percentage_id'],
                "order_status_id" => $this->order['order_status_id'],
                "setter_id" => $this->order['setter_id'],
                "payment_method_id" => $this->order['payment_method_id'],
                "agent_id" => $this->order["agent_id"],
                "hs_deal_id" => $this->order["hs_deal_id"],
                "sevdesk_invoice_id" => $this->order["sevdesk_invoice_id"],
                "sevdesk_user_id" => $this->order["sevdesk_user_id"],
                "gender" => $this->order['gender'],
                "dob" => $this->order['dob'],
                "commission_type" => $this->order['commission_type'],
                "account" => isset($this->order['account']) && $this->order['account'] ? encrypt_hs_string($this->order['account']) : null,
                "b_account" => isset($this->order['b_account']) && $this->order['b_account'] ? encrypt_hs_string($this->order['b_account']) : null,
                "auth_code" => $this->order['auth_code'] ? $this->order['auth_code'] : generate_random_digit(4),
                "paid_amount" => $this->order["paid_amount"],
                "installment_amount" => $this->order["installment_amount"],
                "downpayment_amount" => $this->order["downpayment_amount"],
                "payment_type" => $this->order["payment_type"],
                "installment_frequency" => $this->order["installment_frequency"],
                "installment_start_date" => $this->order["installment_start_date"],
                "is_manual_amount" => $this->order["is_manual_amount"]
            ]);

        $this->emit('saved');
    }



    public function mount ()
    {
        $this->products = Product::where('status_id', '3')->pluck('elopage_product_id', 'name')->toArray();
        $this->commission_types = array("FollowUp Lead oder Paid Lead" => "FollowUp Lead oder Paid Lead", "Cold Lead" => "Cold Lead", "Customer" => "Customer" );
        $this->countries = Country::orderBy('offset', 'desc')->orderBy('name_en', 'asc')->pluck('code', 'name_en')->toArray();
        $this->installments = Installment::where('status_id', 3)->pluck('id', 'name')->toArray();
        $this->vat_percentages = VatPercentage::where('status_id', 3)->pluck('id', 'display_name')->toArray();
        $this->payment_methods = PaymentMethod::where('status_id', 3)->pluck('id', 'name')->toArray();
        $this->genders = array(
                          'Male' => 'm',
                          'Female' => 'f'
                        );

        $this->installment_frequencies = array(
          'Monthly' => 'monthly',
          'Weekly' => 'weekly'
        );

        $this->payment_types = array(
          'One Time' => 'one-time',
          'Installment' => 'installment'
        );

        $this->agents = Agent::get()->pluck('hs_vid', 'full_detail')->toArray();
        $this->order_statuses = array('Generated' => '1');

        if (!$this->order && $this->orderId) {

            $orderId = base64_decode($this->orderId);
            $this->order = Order::find($orderId);
            $this->order['account'] = $this->order['account'] ? decrypt_hs_string($this->order['account']) : null;
            $this->order['b_account'] = $this->order['b_account'] ? decrypt_hs_string($this->order['b_account']) : null;
            $this->order_statuses = OrderStatus::pluck('id', 'name')->toArray();
            if($this->order->order_status_id == 2){
              $this->isDisabled = false;
            }
        }

        if($this->contactId){
          try {
            $contactId = base64_decode($this->contactId);
            if(env('HS_MODE') == "development"){
              $this->hubspot = Factory::createWithAccessToken(base64_decode(env('HUBSPOT_PAT')));
            } else {
              $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
            }
            $propertyList = "firstname, lastname, email, company, city, state, country, address, phone, zip";
            $hubspot_response = $this->hubspot->crm()->contacts()->basicApi()->getById($contactId, $propertyList, null, false, null);
            $properties = $hubspot_response->getProperties();
            $propertyCountry = $properties['country'] ?? null;
            $country = Country::where('name', $propertyCountry)->first();

            $this->order = array(
              'hs_vid' =>  $properties['hs_object_id'] ?? null,
              'firstname' => $properties['firstname'] ?? null,
              'lastname' => $properties['lastname'] ?? null,
              'email' => $properties['email'] ?? null,
              'company' => $properties['company'] ?? null,
              'city' => $properties['city'] ?? null,
              'phone' => $properties['phone'] ?? null,
              'state' => $properties['state'] ?? null,
              'address' => $properties['address'] ?? null,
              'plz' => $properties['zip'] ?? null,
              'order_status_id' => '1',
              'payment_type' => 'one-time',
              'payment_method' => null,
              'installment_id' => 1,
              'is_manual_amount' => 0
            );

            if($country){
              $this->order['country_code'] = $country->code;
            }

          } catch (ApiException $e) {

          }
        }

        $this->button = create_button($this->action, "Order");
    }


    public function changePaymentType($value){
       $this->order['payment_type'] = $value;
       $this->calculateAmount();
    }

    public function changeInstallment($value){
       $this->order['installment_id'] = $value;
       $this->calculateAmount();
    }

    public function changeProduct($value){
       $this->order['product_id'] = $value;
       $this->calculateAmount();
    }

    public function changeAmount($value){
       $this->order['amount'] = $value;
       $this->calculateAmount();
    }

    public function changeManualAmount(){
       $this->order['is_manual_amount'] = $this->order['is_manual_amount'] == 1 ? 0 : 1 ;
    }

    public function changePaymentMethod($value){
       $this->order['payment_method'] = $value;
       $this->calculateAmount();
    }

    public function calculateAmount(){
      if(isset($this->order['product_id']) && isset($this->order['payment_type']) && $this->order['is_manual_amount'] != 1 ){

          $calculatedAmount = ProductAmount::where('elopage_product_id', $this->order['product_id'])->where('installment_id', $this->order['payment_type'] == 'one-time' ? 0 : $this->order['installment_id'])->first();

           if($calculatedAmount){
              $this->order['amount'] = $calculatedAmount->amount;
            } else {
              $this->amount = "";
            }
       }
    }


    public function render()
    {
        return view('livewire.create-order');
    }
}
