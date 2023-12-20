<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;

class CreateSevDeskTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'create:sevdesk_transaction {transaction : The ID of the Transaction}
                         {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SevDesk Transaction';

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
          "valueDate" => $transaction->created_at->format('d.m.Y'),
          "entryDate" => $transaction->created_at->format("Y-m-d\TH:i:s.000\Z"),
          "amount"  => $transaction->amount,
          "feeAmount" => "0",
          "status" => "100",
          "payeePayerName" => $order->firstname." ".$order->lastname,
          "checkAccount[id]" => env('SEVDESK_CHECKACCOUNT'),
          "checkAccount[objectName]" => "CheckAccount"
        );

        $response = Http::withHeaders([
          'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
        ])->asForm()->post('https://my.sevdesk.de/api/v1/CheckAccountTransaction', $data);

        $responseResult = $response->json();
        if($responseResult){
          if(isset($responseResult['objects']['id'])){
              return $responseResult['objects']['id'];
          }
        }

        return false;
    }
}
