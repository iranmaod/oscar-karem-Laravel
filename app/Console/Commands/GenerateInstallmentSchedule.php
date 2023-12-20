<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\InstallmentSchedule;
use App\Models\Transaction;

class GenerateInstallmentSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'generate:installment_schedule {order : The ID of the Order} {reset? : Reset Date from today}
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
    public function handle()
    {

        $orderId = $this->argument('order');
        $isReset = $this->argument('reset');

        $order = Order::where('id', $orderId)->first();
        $installments = generate_installment_schedule($order, true, $isReset);
        if(count($installments) > 0){
          $installmentAmount = $installments[0]['amount'];
          InstallmentSchedule::insert($installments);
          $order->update(array('installment_amount' => $installmentAmount));
        }

        return 0;
    }
}
