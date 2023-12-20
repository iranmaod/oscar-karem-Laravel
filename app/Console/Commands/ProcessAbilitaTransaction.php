<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;

class ProcessAbilitaTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:abilita_transaction {order : The ID of the Order}
                        {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Abilita Transactions';

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
        $order =  Order::where('id', $orderId)->firstOrFail();


        switch($order->payment_method->slug){
          case "abilita-cc":
            $paymentMethod = 'cc';
            break;
          case "abilita-dd":
            $paymentMethod = 'dd';
            break;
          case "abilita-dd_b2b":
            $paymentMethod = 'dd_b2b';
            break;
          case "abilita-paypal":
            $paymentMethod = 'paypal';
            break;
          case "abilita-kar":
            $paymentMethod = 'kar';
            break;
          case "abilita-kar_b2b":
            $paymentMethod = 'kar_b2b';
            break;
          default:
            $paymentMethod = 'cc';
            break;
        }


        $data = array(
          "payment_type"=> $paymentMethod,
          "api_key"=>'8c0d1e91702872b92cbe',
          "amount"=> $order->payment->transaction_amount,
          "success_url"=> route('payment.abilita.success'),
          "postback_url"=> route('payment.abilita.postback'),
          "error_url"=> route('payment.abilita.error'),
          "first_name"=> $order->firstname,
          "last_name"=> $order->lastname,
          "address"=> $order->address,
          "postal_code"=> $order->plz,
          "email"=> $order->email,
          "city"=> $order->city,
          "country"=> strtoupper($order->country->code),
          "order_id"=> $order->id,
          "recurring"=>"1",
          "original_transaction_id" => $order->transaction[0]->transaction_id,
          "additional_transaction_data" => array("is_installment" => false, "installment_id" => null)
        );

        if($paymentMethod == 'kar' || $paymentMethod == 'kar_b2b'){
          $data['date_of_birth'] = $order->dob;
        }

        if($paymentMethod == 'dd' || $paymentMethod == 'dd_b2b'){
          $data["account_holder"]  = $order->firstname.' '.$order->lastname;
          $data['gender'] = 'm';
          $data["iban"] = decrypt_hs_string($order['account']);

          if($order['b_account']){
            $data["bic"] = decrypt_hs_string($order['b_account']);
          }
          $data['date_of_birth'] = $order->dob;
          $data["sepa_mandate"] = decrypt_hs_string($order["sm_token"]);
        }

        $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Authorization' => 'Basic OGMwZDFlOTE3MDI4NzJiOTJjYmU6ZmM2MzFiZjhkMzVlMTJjZDNlYzc='
        ])->post('https://testapi.abilitapay.de/rest/payment', $data);

        $responseData = $response->json();
        if($responseData["error_code"] == 0 &&  isset($responseData["client_action"]) &&$responseData["client_action"] == "redirect"){
          return redirect()->away($responseData['action_data']["url"]);
          Log::channel('single')->info(`Redirect Url for ${orderId} `);
        }

        if($responseData["error_code"] == 0 && $responseData["status"] == "completed"){
          Log::channel('single')->info(`Transaction created for ${orderId} `);
        } else {
          Log::channel('single')->info(`Error in transaction for ${orderId} `);
        }
        return 0;
    }
}
