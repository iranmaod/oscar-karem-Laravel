<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use HubSpot\Factory;
use HubSpot\Client\Crm\Deals\ApiException as DealApiException;
use App\Models\Order;
use HubSpot\Client\Crm\Deals\Model\Filter as DealFilter;
use HubSpot\Client\Crm\Deals\Model\FilterGroup as DealFilterGroup;
use HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest as DealSearchRequest;
use HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput as DealInput;


class CreateHubspotDeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:hubspot_deal {order : The ID of the Order}
                        {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Hubspot Deal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
     public function __construct()
     {
         parent::__construct();
         if(env('HS_MODE') == "development"){
           $this->hubspot = Factory::createWithAccessToken(base64_decode(env('HUBSPOT_PAT')));
         } else {
           $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
         }
     }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderId = $this->argument('order');
        $order = Order::where('id', $orderId)->firstOrFail();
        $properties = array(
          "amount" => $order->amount,
          "createdate" => $order->created_at->format('Y-m-d'),
          "closedate"  => $order->created_at->format('Y-m-d'),
          "dealname" => $order->firstname." ".$order->lastname." - ".$order->product->name,
          "hubspot_owner_id" => $order->agent_id,
          "wie_viele_ratenzahlungen_" => $order->installment->billing_threshold,
          "anzahl" => $order->qty,
          "zahlungsmethode" => $order->payment_method->name,
          "pipeline" => "default",
          "amount" => $order->amount,
          "betrag_1__zahlung" => number_format((float)($order->amount/$order->installment->billing_threshold), 2, '.', ''),
          "comission" => $order->commission_type,
          "abrechnungstyp" => $order->installment->billing_threshold != 1 ? "Ratenzahlung" : "Einmalzahlung",
          "abrechnung" => "inHouse Sales System",
          "deal_currency_code" => "EUR"
        );
        if($order->setter_id  != null && $order->setter_id !=  $order->agent_id){
          $properties["dealstage"] = "presentationscheduled";
          $properties["set_up_caller"] = $order->setter_id;
        } else {
          $properties["dealstage"] = "6606107";
        }

        $dealInput = new DealInput(['properties' => $properties]);
        try {
            $apiResponse = $this->hubspot->crm()->deals()->basicApi()->create($dealInput);
            $results = $apiResponse;
            $id = $results->getID();
            if(isset($id)){
              Order::where('id', $order->id)->update(array(
               'hs_deal_id' => $id
             ));


            $apiResponse = $this->hubspot->crm()->deals()->associationsApi()->create($id, "Contacts", $order->hs_vid, "deal_to_contact");

            return $id;
          }

        } catch (DealApiException $e) {
            dd($e);
        }

        return false;
    }
}
