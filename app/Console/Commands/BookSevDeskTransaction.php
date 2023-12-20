<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;

class BookSevDeskTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'book:sevdesk_transaction {transaction : The ID of the Transaction}
                         {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Book transaction on SevDesk Transaction';

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

          $transactionId = $this->argument('transaction');
          $transaction = Transaction::where('id', $transactionId)->firstOrFail();
          $order = $transaction->order;
          $data = array(
            "amount" => $transaction->amount,
            "date" => $transaction->created_at->format("Y-m-d\TH:i:s.000\Z"),
            "type" => "N",
            "checkAccount[id]" => env('SEVDESK_CHECKACCOUNT'),
            "checkAccount[objectName]" => "CheckAccount",
            "checkAccountTransaction[id]" => $transaction->sevdesk_transaction_id,
            "checkAccountTransaction[objectName]" => "CheckAccountTransaction",
            "createFeed" => false
          );


          $response = Http::withHeaders([
            'Authorization' => base64_decode(env('SEVDESK_TOKEN')),
            'Content-Type' => "application/x-www-form-urlencoded"
          ])->asForm()->put('https://my.sevdesk.de/api/v1/Invoice/'.$order->sevdesk_invoice_id.'/bookAmount', $data);

          $responseResult = $response->json();
          if($responseResult){
            if(isset($responseResult['objects']['id'])){
                return $responseResult['objects']['id'];
            }
          }

        return 0;
    }
}
