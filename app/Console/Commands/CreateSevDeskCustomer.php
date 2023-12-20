<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Carbon\Carbon;

class CreateSevDeskCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'create:sevdesk_customer {order : The ID of the Order}
                         {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SevDesk Customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $customerNumberResponse = Http::withHeaders([
          'Authorization' => base64_decode(env('SEVDESK_TOKEN')),
          'accept' => 'application/json'
        ])->get('https://my.sevdesk.de/api/v1/Contact/Factory/getNextCustomerNumber');

        if(isset($customerNumberResponse->json()["objects"])){
          $orderId = $this->argument('order');
          $order = Order::where('id', $orderId)->firstOrFail();

          $data = array(
            'category[id]' =>  3,
            'category[objectName]'=>'Category',

            'customerNumber' => $customerNumberResponse->json()["objects"],

          );

          if($order->company_name){
            $data['name'] = $order->company_name;
            $data['surename'] = $order->firstname;
            $data['familyname'] = $order->lastname;
          } else {
            $data['surename'] = $order->firstname;
            $data['familyname'] = $order->lastname;
          }

          if($order->vat){
            $data['vatNumber'] = $order->vat;
          }

          $response = Http::withHeaders([
            'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
          ])->asForm()->post('https://my.sevdesk.de/api/v1/Contact', $data);


          if($response->json()){

            $responseData = $response->json();
            if(isset($responseData['objects']['id'])){
              Order::where('id', $order->id)->update(array(
               'sevdesk_user_id' => $responseData['objects']['id']
             ));



             $addressData  =  array(
               'contact[id]'  => $responseData['objects']['id'],
               'contact[objectName]' => 'Contact',
               'street' => $order->address,
               'zip' => $order->plz,
               'city' => $order->city,
               'country[id]' => $order->country->sevdesk_id,
               'country[objectName]' => 'StaticCountry',
               'category[id]' => 47,
               'category[objectName]' => 'Category',
               'name' => $order->firstname.' '.$order->lastname,
             );

             $addressResponse = Http::withHeaders([
               'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
             ])->asForm()->post('https://my.sevdesk.de/api/v1/ContactAddress', $addressData);

             $communicationEmailData  =  array(
               'contact[id]'  => $responseData['objects']['id'],
               'contact[objectName]' => 'Contact',
               'type' => 'EMAIL',
               'value' => $order->email,
               'key[id]' => 8,
               'key[objectName]' => 'CommunicationWayKey',
             );

             $communicationEmailResponse = Http::withHeaders([
               'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
             ])->asForm()->post('https://my.sevdesk.de/api/v1/CommunicationWay', $communicationEmailData);

             $communicationPhoneData  =  array(
               'contact[id]'  => $responseData['objects']['id'],
               'contact[objectName]' => 'Contact',
               'type' => 'PHONE',
               'value' => $order->phone,
               'key[id]' => 8,
               'key[objectName]' => 'CommunicationWayKey',
             );

             $communicationPhoneResponse = Http::withHeaders([
               'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
             ])->asForm()->post('https://my.sevdesk.de/api/v1/CommunicationWay', $communicationPhoneData);


              return $responseData['objects']['id'];
            }
          }
        }

        return false;
    }
}
