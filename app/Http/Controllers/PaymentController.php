<?php

namespace App\Http\Controllers;

use Stripe;
use Carbon\Carbon;
use App\Models\Deal;
use PayPal\Api\Plan;
use App\Models\Order;
use PayPal\Api\Patch;
use App\Models\Payment;
use App\Models\PaymentDetails;
use PayPal\Api\Currency;
use App\Models\OrderStatus;
use App\Models\Transaction;
use PayPal\Api\ChargeModel;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\PatchRequest;
use App\Models\PaymentMethod;
use PayPal\Common\PayPalModel;
use App\Models\DealInstallment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\MerchantPreferences;
use Illuminate\Support\Facades\Http;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Session;
use Facades\App\Supports\PayPal;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use App\Models\InstallmentSchedule;

class PaymentController extends Controller
{

  private $paypalApiContext;
  private $paypal_mode;
  private $paypal_client_id;
  private $paypal_secret;

  /*** Create a new instance with our paypal credentials ***/
  public function __construct()
  {
    // Detect if we are running in live mode or sandbox
    if (config('paypal.settings.mode') == 'live') {
      $this->paypal_client_id = config('paypal.live_client_id');
      $this->paypal_secret = config('paypal.live_secret');
    } else {
      $this->paypal_client_id = config('paypal.sandbox_client_id');
      $this->paypal_secret = config('paypal.sandbox_secret');
    }

    // Set the Paypal API Context/Credentials
    $this->paypalApiContext = new ApiContext(new OAuthTokenCredential($this->paypal_client_id, $this->paypal_secret));
    $this->paypalApiContext->setConfig(config('paypal.settings'));
  }


  /*** Payment method view for Admin Dashboard ***/
  public function method_index_view ()
  {
      return view('pages.payment-method.payment-method-data', [
          'paymentMethod' => PaymentMethod::class
      ]);
  }

  /*** Payment view for Admin Dashboard ***/
  public function index_view ()
  {
      return view('pages.payment.payment-data', [
          'order' => Order::class
      ]);
  }

  /*** Single Payment view for Admin Dashboard ***/
  public function single_view ($orderId)
  {
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();
      $order_statuses = OrderStatus::pluck('id', 'name')->toArray();

      return view('pages.payment.payment-single', compact('order', 'order_statuses'));
  }


  /*** Payment Controller Init ***/
  public function init($orderId, $installmentId = null){
      if(!empty($installmentId)){
          $decodedOrderId = base64_decode($orderId);
          $decodedinstallmentId = base64_decode($installmentId);
          $order = Order::where('id', $decodedOrderId)->where(
            function($query){
                 $query->where('order_status_id', 1)->orWhere('order_status_id', 3);
            })->firstOrFail();
          $vatPercentage = $order->vat_percentage->percentage;
    
          if($order->payment_method->slug == "abilita-cc" || $order->payment_method->slug == "abilita-dd" || $order->payment_method->slug == "abilita-dd_b2b" || $order->payment_method->slug == "abilita-paypal" || $order->payment_method->slug == "abilita-kar" || $order->payment_method->slug == "abilita-kar_b2b"){
                return redirect()->route('payment.abilita.init.install', ['orderId' => $orderId, 'installmentId' => $decodedinstallmentId]);
          }
    
          if($order->payment_method->slug == "kredit-close"){
            return redirect()->route('payment.kredit.init', array('orderId' => $orderId));
          }
         
        } 
      
     
      
          $decodedOrderId = base64_decode($orderId);
          $order = Order::where('id', $decodedOrderId)->where(
            function($query){
                 $query->where('order_status_id', 1)->orWhere('order_status_id', 3);
            })->firstOrFail();
          $vatPercentage = $order->vat_percentage->percentage;

          if($order->payment_method->slug == "abilita-cc" || $order->payment_method->slug == "abilita-dd" || $order->payment_method->slug == "abilita-dd_b2b" || $order->payment_method->slug == "abilita-paypal" || $order->payment_method->slug == "abilita-kar" || $order->payment_method->slug == "abilita-kar_b2b"){
             return redirect()->route('payment.abilita.init', array('orderId' => $orderId));
          }
    
          if($order->payment_method->slug == "kredit-close"){
            return redirect()->route('payment.kredit.init', array('orderId' => $orderId));
          }
  }

  /*** Stripe Controller for Checkout ***/

  public function stripe($orderId)
  {
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();
      return view('pages.order.order-payment-stripe', compact('order'));
  }


   /**
    * success response method.
    *
    * @return \Illuminate\Http\Response
    */
   public function stripePost(Request $request)
   {
       Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
       Stripe\Charge::create ([
               "amount" => 100 * 100,
               "currency" => "usd",
               "source" => $request->stripeToken,
               "description" => "This payment is tested purpose phpcodingstuff.com"
       ]);

       Session::flash('success', 'Payment successful!');

       return back();
   }


   // public function create_transaction(){
   //    $data = array(
   //      "transaction_id" => "Dummy",
   //      "amount" => 100,
   //      "date" => "05-01-2022",
   //      "order_id" => 6,
   //      "payment_id" => 1,
   //      "status" => 1
   //    );
   //    $transaction = Transaction::create($data);
   //    return response()->json([
   //        'name' => 'Transaction created successfully',
   //        'status' => "success"
   //    ]);
   // }

   // public function deal_increment(){
   //   $setter = "36238877";
   //   $startOfMonth = Carbon::createFromFormat('Y-m-d H:i:s', '2019-08-25 17:58:03')->startOfMonth();
   //   $endOfMonth = Carbon::createFromFormat('Y-m-d H:i:s', '2019-08-25 17:58:03')->endOfMonth();
   //   $count = Deal::whereBetween('closedate', [$startOfMonth, $endOfMonth])->where(function($query) use ($setter) {
   //     $query->where('set_up_caller', $setter)->orWhere('hubspot_owner_id', $setter);
   //   })->get();
   //
   //   dd($count);
   // }

/*** Initilize Kredit Close Transaction ***/
  public function kredit_close_init($orderId, $transactionId = ''){
      $orderId = base64_decode($orderId);
      $order =  Order::where('id', $orderId)->firstOrFail();
      Order::where('id', $order->id)->update(array(
        'order_status_id' => '2'
      ));

      $message = request('message');


      $payment = Payment::where('order_id', $orderId)->first();
      $transaction = Transaction::updateOrCreate(
         array(
          'transaction_id' => 'KreditClose-'.$orderId,
         ),
         array(
          'order_id' => $orderId,
          'transaction_id' => 'KreditClose-'.$orderId,
          'payment_id' => $payment->id,
          'amount' => $order->total_amount,
          'payment_method_slug' => $order->payment_method->slug,
          'ip_address' => request()->ip(),
          'status' => "completed",
          'remark' => "Kredit Close Transaction without payment",
          'date' => now()
      ));

      return redirect()->route('order.status', array('orderId' => base64_encode($orderId), 'transactionId' => base64_encode('KreditClose-'.$orderId), 'status' => base64_encode("Success")));
  }

/*** Initilize Abilita Pay Transaction ***/
   public function abilita_init($orderId, $transactionId = ''){
       
      $live_api_key = "267bfdb4e6779374d2a2";
      $live_out_key = "4acf7df75c8db38e6c54";
      $key = base64_encode($live_api_key.':'.$live_out_key);
      
      $test_api_key = "8c0d1e91702872b92cbe";
      $test_out_key = "fc631bf8d35e12cd3ec7";
      $test_key = base64_encode($test_api_key.':'.$test_out_key);

      $orderId = base64_decode($orderId);
      $order =  Order::where('id', $orderId)->firstOrFail();
      $paymentMethod = 'cc';
      $paymentType = $order->payment_type;

      if($paymentType == 'installment'){
        $amount = $order->transaction->where('status', 'completed')->count() ? $order->installment_amount : $order->downpayment_amount;
      } else {
        $amount = $order->amount;
      }




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
        // "api_key"=> "8c0d1e91702872b92cbe",
        // "api_key"=> $live_api_key,
        "api_key"=> $test_api_key,
        "amount"=> $amount,
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
        "recurring"=>"0",
        "additional_transaction_data" => http_build_query(array("is_installment" => false, "installment_id" => null))
      );

      if($paymentType == 'installment'){
        $data['recurring'] = "1";
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

        /*LIVE*/
        // $mandateResponse = Http::withHeaders([
        //   'Content-Type' => 'application/json',
        //   'Authorization' => "Basic $key"
        // ])->post('https://api.abilitapay.de/rest/create_mandate_reference', $data);

        /*TEST*/
        $mandateResponse = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Authorization' => "Basic $test_key"
        ])->post('https://testapi.abilitapay.de/rest/create_mandate_reference', $data);

        $mandateResponseData = $mandateResponse->json();

        if($mandateResponseData["token"]){
          if($paymentType == 'installment'){
            $order->update(array('sm_token' => encrypt_hs_string($mandateResponseData["token"])));

          }
          $data["sepa_mandate"] = $mandateResponseData["token"];
        }
      }

// echo '<pre>';
// print_r($paymentType);
// echo '<br>';
// print_r($paymentMethod);
// echo '<br>';
// print_r($data);
// echo '<br>';
// print_r($order);
// exit;
      if($paymentMethod == 'paypal' && $paymentType == "installment"){
          $data['paypal_plan_id'] =  "P-6UK2193323222643NMFJN66Q";  //$this->create_paypal_plan($order);
      }

      if($transactionId != ""){
        $transactionId = base64_decode($transactionId);
        $data["original_transaction_id"] = $transactionId;
      }

    /*LIVE*/
    //   $response = Http::withHeaders([
    //     'Content-Type' => 'application/json',
    //     'Authorization' => "Basic $key"
    //   ])->post('https://api.abilitapay.de/rest/payment', $data);

    /*TEST*/
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => "Basic $test_key"
      ])->post('https://testapi.abilitapay.de/rest/payment', $data);

      $responseData = $response->json();
// echo '<pre>';print_r($responseData);exit;
      if($responseData["error_code"] == 0 && isset($responseData["client_action"]) &&$responseData["client_action"] == "redirect"){
        return redirect()->away($responseData['action_data']["url"]);
      }
      if($responseData["error_code"] == 0 && !isset($responseData["client_action"])){
        $status = base64_encode($responseData["status"]);
        $transactionId =  base64_encode($responseData["transaction_id"]);
        $orderId = base64_encode($orderId);
        return redirect()->route('order.status', array('orderId' => $orderId, 'transactionId' => $transactionId, 'status' => $status));
      }

      if($responseData["error_code"] != 0 ){
         return redirect()->route('payment.abilita.error', array("order_id" => $order->id, "transaction_id" => "Not Generated. " . $responseData["error_message"] . ' (Error Code: ' .$responseData["error_code"] . ')'));
      }
 
   }


  /*** Initilize Abilita Pay Transaction for installment ***/
  public function abilita_init_install($orderId, $installmentId){
    $live_api_key = "267bfdb4e6779374d2a2";
    $live_out_key = "4acf7df75c8db38e6c54";
    $key = base64_encode($live_api_key.':'.$live_out_key);
      
    $test_api_key = "8c0d1e91702872b92cbe";
    $test_out_key = "fc631bf8d35e12cd3ec7";
    $test_key = base64_encode($test_api_key.':'.$test_out_key);

    $orderId = base64_decode($orderId);

    $order =  Order::where('id', $orderId)->firstOrFail();

    $paymentMethod = 'cc';
    $paymentType = $order->payment_type;

    if($paymentType == 'installment'){
      $amount = $order->transaction->where('status', 'completed')->count() ? $order->installment_amount : $order->downpayment_amount;
      $amount = $amount == '0.00' ? $order->installment_amount : $amount;
    } else {
      $amount = $order->amount;
    }

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
        // "api_key"=> "8c0d1e91702872b92cbe",
        // "api_key"=> $live_api_key,
        "api_key"=> $test_api_key,
        "amount"=> $amount,
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
        "invoice_id"=> $installmentId,
        "recurring"=>"0",
        "additional_transaction_data" => http_build_query(array("is_installment" => true, "installment_id" => $installmentId))
      );


      if($paymentType == 'installment'){
        $data['recurring'] = "1";
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


        $mandateResponse = Http::withHeaders([
          'Content-Type' => 'application/json',
        //   'Authorization' => 'Basic OGMwZDFlOTE3MDI4NzJiOTJjYmU6ZmM2MzFiZjhkMzVlMTJjZDNlYzc='
          'Authorization' => "Basic $key"
        ])->post('https://api.abilitapay.de/rest/create_mandate_reference', $data);

        $mandateResponseData = $mandateResponse->json();

        if($mandateResponseData["token"]){
          if($paymentType == 'installment'){
            $order->update(array('sm_token' => encrypt_hs_string($mandateResponseData["token"])));

          }
          $data["sepa_mandate"] = $mandateResponseData["token"];
        }
      }

      if($paymentMethod == 'paypal' && $paymentType == "installment"){
          $data['paypal_plan_id'] =  "P-6UK2193323222643NMFJN66Q";  //$this->create_paypal_plan($order);
      }

      if($order->transaction[0]){
        $data["original_transaction_id"] = $order->transaction[0]->transaction_id;
      }

      /*LIVE*/
    //   $response = Http::withHeaders([
    //     'Content-Type' => 'application/json',
    //       'Authorization' => "Basic $key"
    //   ])->post('https://api.abilitapay.de/rest/payment', $data);

      /*TEST*/
      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
          'Authorization' => "Basic $test_key"
      ])->post('https://testapi.abilitapay.de/rest/payment', $data);

      $responseData = $response->json();
    
      if($responseData["error_code"] == 0 && isset($responseData["client_action"]) &&$responseData["client_action"] == "redirect"){
        return redirect()->away($responseData['action_data']["url"]);
      }
      if($responseData["error_code"] == 0 && !isset($responseData["client_action"])){
        $status = base64_encode($responseData["status"]);
        $transactionId =  base64_encode($responseData["transaction_id"]);
        $orderId = base64_encode($orderId);
        
        return redirect()->route('order.status.installment', array('orderId' => $orderId, 'transactionId' => $transactionId, 'status' => $status, 'installmentId' =>  $installmentId));
      }

      if($responseData["error_code"] != 0 ){
         return redirect()->route('payment.abilita.error', array("order_id" => $order->id, "transaction_id" => "Not Generated"));
      }

   }
   
   
/*** On Abilita Postback  ***/
   public function abilita_postback(){
    //   echo'POST BACK:- <pre>';print_r(request()->all());exit;
     $transactionId = request('transaction_id');
     $orderId = request('order_id');
     $status = request('status');
     $message = request('message');
     $additional_data = false;

     $additional_transaction_data = request('additional_transaction_data');
     parse_str($additional_transaction_data, $parse_result);
     $additional_data = $parse_result;

     $payment = Payment::where('order_id', $orderId)->latest('id')->first();
     $order = Order::where('id', $orderId)->first();

     if($order->payment_type == 'installment'){
       if($order->transaction->count() && $order->transaction[0]->transaction_id != $transactionId){
         $amount = $order->installment_schedules[0]->amount;
       } else {
         $amount = $order->downpayment_amount;
       }
     } else {
       $amount = $order->total_amount;
     }
    //  dd($order);
     $transaction = Transaction::updateOrCreate(
        array(
         'transaction_id' => $transactionId,
        ),
        array(
         'order_id' => $orderId,
         'transaction_id' => $transactionId,
         'payment_id' => $payment->id,
         'payment_method_slug' => $order->payment_method->slug,
         'ip_address' => $order->ip_address,
         'amount' => $amount,
         'status' => $status,
         'remark' => $message ? "Abilita Pay: ".$message : "Abilita Pay",
         'date' => now(),
         'is_installment' => isset($additional_data["is_installment"]) ? $additional_data["is_installment"] : 0,
         'installment_id' => isset($additional_data["installment_id"]) ? $additional_data["installment_id"] : null
     ));




   }

/*** On Abilita Success  ***/
    public function abilita_success(){
        // $live_api_key = "267bfdb4e6779374d2a2";
        // $live_out_key = "4acf7df75c8db38e6c54";
          
        // $key = base64_encode($live_api_key.':'.$live_out_key);
      
        // $test_api_key = "8c0d1e91702872b92cbe";
        // $test_out_key = "fc631bf8d35e12cd3ec7";
        // $test_key = base64_encode($test_api_key.':'.$test_out_key);

        // $data = [
        // //   "api_key"=> "8c0d1e91702872b92cbe",
        // //   "api_key"=> $live_api_key,
        //   "api_key"=> $test_api_key,
        //     "transaction_id"=> request('transaction_id'),
        // ];
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        // //   'Authorization' => 'Basic OGMwZDFlOTE3MDI4NzJiOTJjYmU6ZmM2MzFiZjhkMzVlMTJjZDNlYzc='
        //   'Authorization' => "Basic $key"
        // ])->get('https://api.abilitapay.de/rest/transactions/'.request('transaction_id'), $data);

        // $responseData = $response->json();

        // parse_str($responseData['additional_transaction_data'], $output);
        
        // if ($output["is_installment"]) {
        //     $installment_id = $output["installment_id"];
        //     // echo $installment_id . '<pre>'; print_r($responseData); print_r(InstallmentSchedule::where('order_id', $responseData['order_id'])->where('id', $installment_id)->first()); exit;
        //     InstallmentSchedule::where('id', $installment_id)->update(array(
        //         'transaction_id' => request('transaction_id'),
        //         'paid_date' => date('Y-m-d H:i:s', time()),
        //         'updated_at' => date('Y-m-d H:i:s', time())
        //     ));
        // }

        // echo'SUCCESS:- <pre>';
        // print_r(request()->all());
        // print_r($responseData);
        // print_r($responseData['additional_transaction_data']);
        // print_r($output);
        // exit;

        $orderId = base64_encode(request('order_id'));
        $transactionId = base64_encode(request('transaction_id'));
        $checksum = request('checksum');
        $status = base64_encode("success");

        return redirect()->route('order.status', array('orderId' => $orderId, 'transactionId' => $transactionId, 'status' => $status));
   }

/*** On Abilita Error  ***/
   public function abilita_error(){
     $orderId = base64_encode(request('order_id'));
     $transactionId = base64_encode(request('transaction_id'));
     $checksum = request('checksum');
     $status = base64_encode("error");

     return redirect()->route('order.status', array('orderId' => $orderId, 'transactionId' => $transactionId, "status" => $status));
   }


/*** Create Paypal Plan ***/
  private function create_paypal_plan($order)
  {


    $installmentFrequency = $order->installment_frequency;

    $amount = $order->amount + calculate_vat_amount($order->amount, @$order->vat_percentage->percentage);
    $installmentCycle = $order->installment->billing_threshold;
    $installment_amount = $amount / $installmentCycle;
    // Create a new billing plan
    $plan = new Plan();
    $plan->setName($installmentFrequency == 'monthly' ?  'Oscar Karem Monthly Billing' :'Oscar Karem Weekly Billing')
      ->setDescription($installmentFrequency == 'monthly' ?  'Monthly Subscription to the Oscar Karem' :'Weekly Subscription to the Oscar Karem')
      ->setType('infinite');

    // Set billing plan definitions
    $paymentDefinition = new PaymentDefinition();
    $paymentDefinition->setName('Regular Payments')
      ->setType('REGULAR')
      ->setFrequency($installmentFrequency == 'monthly' ? 'Month': 'Week')
      ->setFrequencyInterval('1')
      ->setCycles($installmentCycle)
      ->setAmount(new Currency(array('value' => $installment_amount, 'currency' => 'EUR')));

    // Set merchant preferences
    $merchantPreferences = new MerchantPreferences();
    $merchantPreferences->setReturnUrl(route('payment.abilita.success'))
      ->setCancelUrl(route('payment.abilita.error'))
      ->setAutoBillAmount('yes')
      ->setInitialFailAmountAction('CONTINUE')
      ->setMaxFailAttempts('2');

    $plan->setPaymentDefinitions(array($paymentDefinition));
    $plan->setMerchantPreferences($merchantPreferences);

    //create the plan
    try {
      $createdPlan = $plan->create($this->apiContext);

      try {
        $patch = new Patch();
        $value = new PayPalModel('{"state":"ACTIVE"}');
        $patch->setOp('replace')
          ->setPath('/')
          ->setValue($value);
        $patchRequest = new PatchRequest();
        $patchRequest->addPatch($patch);
        $createdPlan->update($patchRequest, $this->paypalApiContext);
        $plan = Plan::get($createdPlan->getId(), $this->paypalApiContext);

        // Output plan id
        return $plan->getId();
      } catch (\PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
      } catch (\Exception $ex) {
        die($ex);
      }
    } catch (\PayPal\Exception\PayPalConnectionException $ex) {
      echo $ex->getCode();
      echo $ex->getData();
      die($ex);
    } catch (\Exception $ex) {
      die($ex);
    }
  }
}
