<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Carbon\Carbon;

class CreateElopageOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'create:elopage_order {order : The ID of the Order}
                         {--queue : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Order in Elopage';

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
      $data = array(
        'product_id' => $order->product_id,
        'email' => $order->email,
        'first_name' => $order->firstname,
        'last_name' => $order->lastname,
        'key' =>  env('ELOPAGE_API_KEY'),
        'secret' => env('ELOPAGE_API_SECRET')
      );

      $elopage_response = Http::withHeaders([
         'Content-Type' => 'application/json',
      ])->post("https://api.elopage.com/api/orders", $data);

      $elopage_order = $elopage_response->json();

      if(isset($elopage_order["order"]["id"])){
        $elopage_order_id = $elopage_order["order"]["id"];
        $elopage_order_token = $elopage_order["order"]["token"];

        Order::where('id', $orderId)->update(array(
          'elopage_order_id' => $elopage_order_id,
          'elopage_order_token' => $elopage_order_token
        ));

        return $elopage_order["order"]["id"];
      } else {
        return false;
      }
        return 0;
    }
}
