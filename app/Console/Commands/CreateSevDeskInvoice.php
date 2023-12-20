<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Carbon\Carbon;

class CreateSevDeskInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:sevdesk_invoice {order : The ID of the Order}
                        {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SevDesk Invoice';

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
      $orderId = $this->argument('order');
      $order = Order::where('id', $orderId)->firstOrFail();
      $title = 'Herr/Frau';
       if($order->gender == 'm'){
         $title = 'Herr';
       }

       if($order->gender == 'f'){
         $title = 'Frau';
       }




      if($order->sevdesk_user_id){
        $data = array(
          'invoice[contact][id]' => $order->sevdesk_user_id,
          'invoice[contact][objectName]' => 'Contact',
          'invoice[invoiceDate]' => $order->created_at->format('d.m.Y'),
          'invoice[discount]' => '0',
          'invoice[deliveryDate]' => $order->created_at->format('d.m.Y'),
          'invoice[status]' => '200',
          'invoice[smallSettlement]' => 'true',
          'invoice[contactPerson][id]' => env('SEVDESK_USER'),
          'invoice[contactPerson][objectName]' => 'SevUser',
          'invoice[taxRate]' => $order->vat_percentage->percentage,
          'invoice[taxText]' => $order->vat_percentage->name,
          'invoice[taxType]' => 'custom',
          'invoice[invoiceType]' => 'RE',
          'invoice[currency]' => 'EUR',
          'invoice[mapAll]' => 'true',
          'invoice[objectName]' => 'Invoice',
          'invoice[taxSet][id]' => $order->vat_percentage->sevdesk_taxset_id,
          'invoice[taxSet][objectName]' => 'TaxSet',
          'invoice[headText]' => 'Sehr geehrter '.$title.' '.$order->firstname.' '.$order->lastname.', <br/><br/> vielen Dank für Ihren Auftrag und das damit verbundene Vertrauen!<br/>Hiermit stellen wir Ihnen die folgenden Leistungen in Rechnung:<br/> ',
          'invoice[footText]' => 'Bitte überweisen Sie den Rechnungsbetrag unter der Angabe der Rechnungsnummer auf das unten angegebene Konto.<br/> Der Rechnungsbetrag ist sofort fällig.<br/><br/>Mit freundlichen Grüßen<br/> BlackWood Gmbh <br/><br/><small> Order #'.$orderId.'</small>',
          'invoice[sendDate]' => Carbon::now()->format('d.m.Y'),
          'invoice[payDate]' => $order->created_at->format('d.m.Y'),
          'invoice[addressName]'=> $order->firstname." ".$order->lastname,
          'invoice[addressStreet]'=> $order->address." ".$order->lot,
          'invoice[addressZip]'=> $order->plz,
          'invoice[addressCity]' => $order->city,
          'invoice[addressCountry][id]' => $order->country->sevdesk_id,
          'invoice[addressCountry][objectName]' => 'StaticCountry',
          'invoicePosSave[0][unity][id]' => '1',
          'invoicePosSave[0][unity][objectName]' => 'Unity',
          'invoicePosSave[0][taxRate]' => $order->vat_percentage->percentage,
          'invoicePosSave[0][mapAll]' => 'true',
          'invoicePosSave[0][objectName]' => 'InvoicePos',
          'invoicePosSave[0][quantity]' => '1',
          'invoicePosSave[0][price]' => floatval($order->amount),
          'invoicePosSave[0][name]' => @$order->product->name,
          'invoicePosSave[0][text]' => '',
          'invoicePosDelete' => 'null',
          'discountSave' => 'null',
          'discountDelete' => 'null',
          'takeDefaultAddress' => 'true'
        );



        $response = Http::withHeaders([
          'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
        ])->asForm()->post('https://my.sevdesk.de/api/v1/Invoice/Factory/saveInvoice', $data);

      }

      if(isset($response) && $response->json()){
        $response = $response->json();


        if(isset($response['objects']['invoice']['id'])){



          $invoiceHeader = Http::withHeaders([
            'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
          ])->get('https://my.sevdesk.de/api/v1/Invoice/'.$response['objects']['invoice']['id'], $data);

          if($invoiceHeader->json()["objects"][0]["invoiceNumber"]){
            $updateHeaderResponse = Http::withHeaders([
              'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
            ])->asForm()->put('https://my.sevdesk.de/api/v1/Invoice/'.$response['objects']['invoice']['id'], array('header' => "Rechnung Nr. ".$invoiceHeader->json()["objects"][0]["invoiceNumber"]));

          }



          Order::where('id', $order->id)->update(array(
           'sevdesk_invoice_id' => $response['objects']['invoice']['id']
         ));
          return $response['objects']['invoice']['id'];

        } else {
          return false;
        }
      }
        return 0;
    }
}
