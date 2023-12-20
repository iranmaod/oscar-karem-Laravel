<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use HubSpot\Client\Crm\Deals\ApiException as DealApiException;
use App\Models\Order;
use App\Models\Deal;
use HubSpot\Client\Crm\Deals\Model\Filter as DealFilter;
use HubSpot\Client\Crm\Deals\Model\FilterGroup as DealFilterGroup;
use HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest as DealSearchRequest;
use HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput as DealInput;

class CreateHubspotDeal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $orderId;
    protected $hubspot

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
        $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();

      $properties = array(
        "amount" => $order->amount,
        "createdate" => $order->created_at->format('Y-m-d'),
        "dealname" => "HSE - #".$order->id." - ".$order->email." - ".$order->product->name,
        "dealstage" => "presentationscheduled",
        "hubspot_owner_id" => $order->agent_id,
        "pipeline" => "default",
      );


      $dealInput = new DealInput(['properties' => $properties]);
      try {
          $apiResponse = $this->hubspot->crm()->deals()->basicApi()->create($dealInput);
          $results = $apiResponse;
          $id = $results->getID();
          if(isset($id)){
            Order::where('id', $order->id)->update(array(
             'hs_deal_id' => $id
           ));
          Log::channel('single')->info('HS Deal created for '.$this->orderId);
        }

      } catch (DealApiException $e) {
          Log::channel('single')->info('Error creating HS Deal for '.$this->orderId);
      }
    }
}
