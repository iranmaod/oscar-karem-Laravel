<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Carbon\Carbon;

class SendSevDeskInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'send:sevdesk_invoice {order : The ID of the Order}
                         {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     /**
      * Execute the console command.
      *
      * @return int
      */
     public function handle()
     {
      $text = "Sehr geehrte Damen und Herren,<br/><br/>";
      $text.= "vielen Dank für Ihren Auftrag. Ihre Rechnung befindet sich im Anhang.<br/><br/>";
      $text.= "Mit freundlichen Grüßen.<br/><br/>";
      $text.= "Blackwood GmbH<br/>";
      $text.= "Karl-Farkas-Gasse 22/9, 1030 Wien, AT<br/>";
      $text.= "ATU 74884039<br/>";
      $text.= "rechnung@blackwood.at<br/><br/>";
      $text.= "Es gelten die <a href='https://www.blackwood.at/agb/' target='_blank'>AGB</a> der Blackwood GmbH";


       $orderId = $this->argument('order');
       $order = Order::where('id', $orderId)->firstOrFail();
       $invoiceId = $order->sevdesk_invoice_id;


       $invoiceHeader = Http::withHeaders([
         'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
       ])->get('https://my.sevdesk.de/api/v1/Invoice/'.$invoiceId);

       if($invoiceHeader->json()["objects"][0]["invoiceNumber"]){
         $data = array(
           'toEmail' => $order->email,
           'subject' => 'Rechnung '.$invoiceHeader->json()["objects"][0]["invoiceNumber"].' - BlackWood GmbH',
           'text' => $text
         );

         $response = Http::withHeaders([
           'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
         ])->asForm()->post('https://my.sevdesk.de/api/v1/Invoice/'.$invoiceId.'/sendViaEmail', $data);

          if($response->json()){
              return $response->json()['objects']['id'];
          }
       }
         return false;
     }
}
