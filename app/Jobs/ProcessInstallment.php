<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Mail\PaymentSuccess;
use App\Mail\PaymentFailed;
use Mail;
class ProcessInstallment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $duePayment;


    public function __construct($duePayment)
    {
        $this->duePayment = $duePayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $orderId = $this->duePayment->order_id;
      $installmentId = $this->duePayment->id;

      $order =  Order::where('id', $orderId)->first();
      if($order){
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
          case "abilita-kar":
            $paymentMethod = 'kar';
            break;
          case "abilita-kar_b2b":
            $paymentMethod = 'kar_b2b';
            break;
          default:
              return 0;
            break;
        }

      }

      $data = array(
        "payment_type"=> $paymentMethod,
        "api_key"=>'8c0d1e91702872b92cbe',
        "amount"=> $order->installment_amount,
        "success_url"=> route('payment.abilita.success'),
        "postback_url"=> "https://eoew3e0ne4dhb8y.m.pipedream.net",
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
        "original_transaction_id" => $order->payment->original_transaction_id,
        "additional_transaction_data" => http_build_query(array("is_installment" => true, "installment_id" => $installmentId))
      );

      if ($order->transaction[0]) {
          $data["original_transaction_id"] = $order->transaction[0]->transaction_id;
      }

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
      Log::channel('single')->info($responseData);

      if($responseData["error_code"] == 0 &&  isset($responseData["client_action"]) &&$responseData["client_action"] == "redirect"){
        //return redirect()->away($responseData['action_data']["url"]);
        Log::channel('single')->info('Redirect Url for '.$orderId);
      }

      if($responseData["error_code"] == 0 && $responseData["status"] == "completed"){
          // Mail::to($order->email)->send(new PaymentSuccess($orderId));
          // Mail::to('rechnung@blackwood.at')->send(new PaymentSuccess($orderId));
        Log::channel('single')->info('Transaction created for '.$orderId);
      } else {
          // Mail::to($order->email)->send(new PaymentFailed($orderId));
          // Mail::to('rechnung@blackwood.at')->send(new PaymentFailed($orderId));
        Log::channel('single')->info('Error in transaction for '.$orderId);
      }
      return 0;

    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send user notification of failure, etc...
    }
}
