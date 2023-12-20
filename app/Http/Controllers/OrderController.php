<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAmount;
use Illuminate\Support\Facades\Http;
use App\Models\Installment;
use App\Models\VatPercentage;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Mail\OrderCheckout;
use App\Mail\InstallmentDueMail;
use App\Mail\PaymentSuccess;
use App\Mail\PaymentFailed;
use App\Models\InstallmentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

use Mail;

class OrderController extends Controller
{

    /***
     * Index all the orders
     */

     public function index_view ()
     {
         return view('pages.order.order-data', [
             'order' => Order::class
         ]);
     }


     /***
      * Index all the orders
      */

      public function product_view ()
      {
          return view('pages.product.product-data', [
              'product' => Product::class
          ]);
      }


      /***
       * Index all the orders
       */

       public function product_amount_view ()
       {
           return view('pages.product-amount.product-amount-data', [
               'productAmount' => ProductAmount::class
           ]);
       }


      /*** List all the products of Elopage under Products > Elopage Product List ***/

      public function list_product_view(){
          $products = Product::pluck('elopage_product_id')->toArray();
          $elopage_api_key =  base64_decode(env('ELOPAGE_API_KEY'));
          $elopage_api_secret = base64_decode(env('ELOPAGE_API_SECRET'));
          $elopage_response = Http::withHeaders([
                'Content-Type' => 'application/json'
          ])->get("https://api.elopage.com/api/products?key=".$elopage_api_key."&secret=".$elopage_api_secret);
          $elopageProductsArr = $elopage_response->json();
          $elopageProducts = array();

           if($elopageProductsArr){
              foreach($elopageProductsArr as $product){
                if($product['free'] === true){
                  array_push($elopageProducts, array(
                    'name' => $product['name'],
                    'elopage_product_id' => $product['id'],
                    'generated' => in_array($product['id'], $products)
                  ));
                }
              }
           }

        return view('pages.product.product-list', compact('elopageProducts'));
      }


    /***
     * Save the order data from Hubspot Contact
     */

    public function save(){

      $elopage_api_key =  env('ELOPAGE_API_KEY');
      $elopage_api_secret = env('ELOPAGE_API_SECRET');

      $elopage_product = request('elopage_product_id');
      $elopage_product = base64_decode($elopage_product);
      $elopage_product = explode("#^#", $elopage_product);
      $elopage_product_id = $elopage_product[0];
      $elopage_product_name = $elopage_product[1];

      if(!Product::where('elopage_product_id', $elopage_product_id)->first()){
          Product::create(array(
              'elopage_product_id' => $elopage_product_id,
              'name' => $elopage_product_name
          ));
      }


      $hs_vid = request('hs_vid');
      $firstname  = request('firstname');
      $lastname = request('lastname');
      $email = request('email');
      $phone = request('phone');
      $address = request('address');
      $installment_id = request('installment_id');
      $lot = request('lot');
      $city = request('city');
      $country = request('country');
      $plz = request('plz');
      $company_name = request('company_name');
      $vat = request('vat');
      $product_id = $elopage_product_id;
      $vat_percentage_id = request('vat_percentage_id');
      $order_status_id = 1;
      $order_start_date = '';
      $order_end_date = '';


      $order = Order::create([
        'hs_vid' => $hs_vid,
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'email' => $email,
        'phone' => $phone,
        'installment_id' => $installment_id,
        'product_id' => $elopage_product_id,
        'address' => $address,
        'lot' => $lot,
        'city' => $city,
        'country' => $country,
        'plz' => $plz,
        'vat' => $vat,
        'company_name' => $company_name,
        'vat_percentage_id' => $vat_percentage_id,
        'order_status_id' => $order_status_id,
      ]);

      return redirect()->route('order.edit', ['id' => base64_encode($order->id)]);

    }


    /***
     * Edit the order page
     */

     public function edit_view ($id)
     {   $id = base64_decode($id);
         $order = Order::where('id', $id)->firstOrFail();
         $installments = Installment::where('status_id', 3)->get();
         $vat_percentages = VatPercentage::where('status_id', 3)->get();

         $elopage_api_key =  env('ELOPAGE_API_KEY');
         $elopage_api_secret = env('ELOPAGE_API_SECRET');
         $elopage_response = Http::withHeaders([
               'Content-Type' => 'application/json',
               'X-Second' => 'bar'
         ])->get("https://api.elopage.com/api/products?key=".$elopage_api_key."&secret=".$elopage_api_secret);


         $products = $elopage_response->json();
         return view('pages.order.order-edit', compact('order', 'products', 'installments', 'vat_percentages'));
     }


    /***
     * Save the order data from Hubspot Contact
     */

    public function update(){
      $order_id = request('order_id');
      $order_id = base64_decode($order_id);

      $elopage_product = request('elopage_product_id');
      $elopage_product = base64_decode($elopage_product);
      $elopage_product = explode("#^#", $elopage_product);
      $elopage_product_id = $elopage_product[0];
      $elopage_product_name = $elopage_product[1];

      if(!Product::where('elopage_product_id', $elopage_product_id)->first()){
          Product::create(array(
              'elopage_product_id' => $elopage_product_id,
              'name' => $elopage_product_name
          ));
      }


      $firstname  = request('firstname');
      $lastname = request('lastname');
      $email = request('email');
      $phone = request('phone');

      $installment_id = request('installment_id');
      $lot = request('lot');
      $city = request('city');
      $country = request('country');
      $plz = request('plz');
      $company_name = request('company_name');
      $vat = request('vat');
      $product_id = $elopage_product_id;
      $vat_percentage_id = request('vat_percentage_id');
      $order_status_id = 0;
      $order_start_date = '';
      $order_end_date = '';


      $order = Order::where('id', $order_id)->update([
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'email' => $email,
        'phone' => $phone,
        'installment_id' => $installment_id,
        'product_id' => $elopage_product_id,
        'lot' => $lot,
        'city' => $city,
        'country' => $country,
        'plz' => $plz,
        'vat' => $vat,
        'company_name' => $company_name,
        'vat_percentage_id' => $vat_percentage_id,
        'order_status_id' => $order_status_id,
      ]);

      return redirect()->route('order.edit', ['id' => base64_encode($order_id)]);

    }

    /*** Checkout the order form ***/

    public function checkout($orderId){

        $orderId = base64_decode($orderId);
        $order = Order::where('id', $orderId)->firstOrFail();
        $installments = Installment::where('status_id', 3)->get();
        $vat_percentages = VatPercentage::where('status_id', 3)->get();
        $payment_methods = PaymentMethod::where('status_id', 3)->get();
        $products = array(array('id' => $order->product->id, 'name' => $order->product->name));
        $installment_schedule = InstallmentSchedule::where('order_id', $orderId)->get();


        return view('pages.order.order-checkout', compact('order', 'installments', 'vat_percentages', 'products', 'payment_methods', 'installment_schedule'));
    }

    /*** set_order_status ***/

    public function set_order_status($orderId, $statusId){
      $orderId = base64_decode($orderId);
      $statusId = base64_decode($statusId);

      Order::where('id', $orderId)->update(array('order_status_id' => $statusId));

      return redirect()->back();

    }

    /*** Checkout the process ***/

    public function process_checkout(){
        $orderId = request('order_id');
        
        $orderId = base64_decode($orderId);

        $order = Order::where('id', $orderId)->firstOrFail();

        $ip_address = request()->ip();
        $auth_code = generate_random_digit(4);

        $payment = Payment::create(array(
          'order_id' => $orderId,
          'original_transaction_id' =>  $order->payment_method->slug == "kredit-close" ? 'KreditClose-'.$orderId : null,
          'start_date' => Carbon::now()->format('Y-m-d'),
          'payment_method_slug' => $order->payment_method->slug
        ));
       

        if(!$order->sevdesk_user_id){
          Artisan::call('create:sevdesk_customer', [
            'order' => $orderId, '--queue' => 'default'
          ]);
        }

        if(!$order->sevdesk_invoice_id){
          Artisan::call('create:sevdesk_invoice', [
            'order' => $orderId, '--queue' => 'default'
          ]);
        }

        if($order->order_status_id == 1){

          Mail::to($order->email)->send(new OrderCheckout($orderId));
          Order::where('id', $orderId)->update(['order_status_id' => 1, 'ip_address' => $ip_address, 'auth_code' => $auth_code]);
        }

        if($order->payment_type == 'installment'){
          Artisan::call('generate:installment_schedule', [
            'order' => $orderId, '--queue' => 'default'
          ]);
        }
        return redirect()->route('order.checkout.preoptin', array('orderId' => base64_encode($order->id)));
    }
    
    
    
    /*****send installment reminder email start**/
    
    public function process_email($id, $orderId){


        $installmentId = InstallmentSchedule::find($id)->id;
         
         
      

        $order = Order::where('id', $orderId)->firstOrFail();

        $ip_address = request()->ip();
        $auth_code = generate_random_digit(4);

        $payment = Payment::create(array(
          'order_id' => $orderId,
          'original_transaction_id' =>  $order->payment_method->slug == "kredit-close" ? 'KreditClose-'.$orderId : null,
          'start_date' => Carbon::now()->format('Y-m-d'),
          'payment_method_slug' => $order->payment_method->slug
        ));

        if(!$order->sevdesk_user_id){
          Artisan::call('create:sevdesk_customer', [
            'order' => $orderId, '--queue' => 'default'
          ]);
        }

        if(!$order->sevdesk_invoice_id){
          Artisan::call('create:sevdesk_invoice', [
            'order' => $orderId, '--queue' => 'default'
          ]);
        }

        if($order->payment_type == 'installment'){
           
         
          Mail::to($order->email)->send(new InstallmentDueMail($orderId, $installmentId));
          Order::where('id', $orderId)->update(['order_status_id' => 1, 'ip_address' => $ip_address, 'auth_code' => $auth_code]);
            /** Commenting because installment id keep changing TALHA **/
        //   Artisan::call('generate:installment_schedule', [
        //     'order' => $orderId, '--queue' => 'default'
        //   ]);
        }
        return redirect()->back();
    }
    
    /*****send installment reminder email end**/

    /*** Process Payment Resume ***/

    public function process_payment_resume(){
      $orderId = request('order_id');
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();

      $payment = Payment::create(array(
        'order_id' => $orderId,
        'original_transaction_id' =>  $order->payment_method->slug == "kredit-close" ? 'KreditClose-'.$orderId : null,
        'start_date' => Carbon::now()->format('Y-m-d'),
        'payment_method_slug' => $order->payment_method->slug
      ));

      $ip_address = request()->ip();
      $auth_code = generate_random_digit(4);

      if($order->payment_type == 'installment'){

        Artisan::call('generate:installment_schedule', [
          'order' => $orderId, 'reset' => true ,'--queue' => 'default'
        ]);
      }

      return redirect()->route('order.payment', ['orderId' => base64_encode($order->id)]);
    }


    /*** Shows the Optin Message to the user ***/

    public function preOptin($orderId){
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();
      return view('pages.order.order-preoptin', compact('order'));
    }

    /*** Shows the optin form to the user ***/

    public function optin($orderId){
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();
      return view('pages.order.order-optin', compact('order'));
    }

    public function process_optin(){
      $orderId = request('order_id');
      $email = request('email');
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();

      if($order->email == $email){
          return redirect()->route('order.payment', ['orderId' => base64_encode($order->id)]);
      } else {
          return redirect()->back()->withErrors(['error' => 'Email address is not same as provided in Order Details']);
      }
    }


    /*** Create a free product on Elopage and update order***/

    protected function create_elopage_order($orderId){
      $orderId = base64_decode($orderId);
      $elopageOrderId = Artisan::call('create:elopage_order', [
        'order' => $orderId, '--queue' => 'default'
      ]);

      if($elopageOrderId){
        return redirect()->back()->with('success', 'Order with id #'.$elopageOrderId.' generated succesfully.');
      } else {
        return redirect()->back()->withErrors(['msg' => 'Unable to generate Elopage Order']);
      }
    }







    /*** Create invoice on SevDesk ***/
    public function create_sevdesk_invoice($orderId){
      $orderId = base64_decode($orderId);
      $invoiceId = Artisan::call('create:sevdesk_invoice', [
        'order' => $orderId, '--queue' => 'default'
      ]);

      if($invoiceId){
        return redirect()->back()->with('success', 'Invoice with id #'.$invoiceId.' generated succesfully.');
      } else {
        return redirect()->back()->withErrors(['msg' => 'Unable to generate invoice']);
      }

    }



        /*** Send invoice on SevDesk ***/
        public function send_sevdesk_invoice($orderId){
          $orderId = base64_decode($orderId);
          $invoiceId = Artisan::call('send:sevdesk_invoice', [
            'order' => $orderId, '--queue' => 'default'
          ]);

          if($invoiceId){
            return redirect()->back()->with('success', 'Invoice with id #'.$invoiceId.' generated succesfully.');
          } else {
            return redirect()->back()->withErrors(['msg' => 'Unable to generate invoice']);
          }

        }



    /*** Create invoice on SevDesk ***/
    public function create_sevdesk_customer($orderId){
      $orderId = base64_decode($orderId);
      $customerId = Artisan::call('create:sevdesk_customer', [
        'order' => $orderId, '--queue' => 'default'
      ]);

      if($customerId){
          return redirect()->back()->with('success', 'Contact with id #'.$customerId.' generated succesfully.');
      } else {
          return redirect()->back()->withErrors(['msg' => 'Error creating the contact']);
      }
    }


    /*** For testing purpose only, Create invoice on SevDesk ***/

    public function create_sevdesk_transaction($orderId, $paymentId){
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();

      if($order->sevdesk_invoice_id){
        $data = array(
          "valueDate" => "01.01.2020",
          "entryDate" => "2022-01-05T10:12:55.498Z",
          "amount"  => "100",
          "feeAmount" => "0",
          "status" => "100",
          "payeePayerName" => $order->firstname." ".$order->lastname,
          "checkAccount[id]" => "5079394",
          "checkAccount[objectName]" => "CheckAccount"
        );

        $response = Http::withHeaders([
          'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
        ])->asForm()->post('https://my.sevdesk.de/api/v1/CheckAccountTransaction', $data);

        $response = $response->json();
        if($response->json()){
          if(isset($response['objects']['id'])){
            Order::where('id', $order->id)->update(array(
             'sevdesk_invoice_id' => $response['objects']['id']
           ));

            return response()->json(array('order_id' => $orderId, 'sevdesk_invoice_id' => $response['objects']['invoice']['id'] ));
          }
        }

      }


    }


    public function book_sevdesk_invoice($transactionId){

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
      ])->asForm()->put('https://my.sevdesk.de/api/v1/Invoice/31176547/bookAmount', $data);

      dd($response);

    }


    public function order_status($orderId, $transactionId, $status){
        
        $orderId = base64_decode($orderId);
        $transactionId = base64_decode($transactionId);
        $status = base64_decode($status);
        $order = Order::where('id', $orderId)->firstOrFail();
        $payment = Payment::where('order_id', $orderId)->latest('id')->first();

        $installmentSchedule = InstallmentSchedule::where('order_id',$orderId)->whereNotNull('paid_date')->count();
        $transaction = Transaction::where('order_id', $orderId)->where('amount', $order->downpayment_amount)->first();
        
        if ( !$transaction && $installmentSchedule == 0) {
            Transaction::updateOrCreate(
                array(
                 'transaction_id' => $transactionId,
                ),
                array(
                 'order_id' => $orderId,
                 'transaction_id' => $transactionId,
                 'payment_id' => $payment->id,
                 'payment_method_slug' => $order->payment_method->slug,
                 'ip_address' => $order->ip_address,
                 'amount' => $order->downpayment_amount,
                 'status' => $status,
                 'remark' => "Abilita Pay",
                 'date' => now(),
                 'is_installment' => 0,
                 'installment_id' => null
            ));
        }
        // echo 'test - 0; ';exit;
        // modifield by talha is below
        // if(!empty($install_schedule)){
        //     $install_schedule->paid_date = date('Y-m-d H:i:s');
        //     $install_schedule->transaction_id = $transactionId;
        //     $install_schedule->update();
        // }

    
        if($status == 'success')
        {
            Mail::to($order->email)->send(new PaymentSuccess($orderId));
            // Mail::to('rechnung@blackwood.at')->send(new PaymentSuccess($orderId));
        }
        else
        {
            Mail::to($order->email)->send(new PaymentFailed($orderId));
            // Mail::to('rechnung@blackwood.at')->send(new PaymentFailed($orderId));
        }

        


        return view('pages.order.status', compact('order', 'transactionId', 'status'));
    }
    
    
    // for installment
     public function order_status_installment($orderId, $transactionId, $status, $installmentId){
        $orderId = base64_decode($orderId);
        $transactionId = base64_decode($transactionId);
        $status = base64_decode($status);
        $order = Order::where('id', $orderId)->firstOrFail();


        // $install_schedule = InstallmentSchedule::where('id',$installmentId)->first();
   
        // $install_schedule->paid_date = date('Y-m-d H:i:s');
        // $install_schedule->transaction_id = $transactionId;
        // $install_schedule->update();

        return view('pages.order.status', compact('order', 'transactionId', 'status'));
    }
    


    public function view($orderId){
      $orderId = base64_decode($orderId);
      $order = Order::where('id', $orderId)->firstOrFail();
      $installments = Installment::where('status_id', 3)->get();
      $vat_percentages = VatPercentage::where('status_id', 3)->get();
      $payment_methods = PaymentMethod::where('status_id', 3)->get();
      $products = array(array('id' => $order->product->id, 'name' => $order->product->name));



      return view('pages.order.order-view', compact('order', 'installments', 'vat_percentages', 'products', 'payment_methods'));
    }

    /*** Generate Installment Schedule ***/

    public function test_installment_schedule($orderId){
      $orderId = base64_decode($orderId);
      $scheduleId = Artisan::call('generate:installment_schedule', [
        'order' => $orderId, '--queue' => 'default', 'reset' => true
      ]);

    }



}
