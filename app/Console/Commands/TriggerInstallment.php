<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\InstallmentSchedule;
use App\Jobs\ProcessInstallment;
use Carbon\Carbon;
use App\Mail\InstallmentDueMail;

use Mail;

class TriggerInstallment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    public $orders;
    public $payments;
    protected $signature = 'trigger:installment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger the due installment at specific schedule';

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

      $duePayments = InstallmentSchedule::whereDate('due_date', Carbon::today())
                                        ->whereNull('paid_date')
                                        ->whereHas('order', function($q){
                                             $q->where('order_status_id', '2');
                                         })->get();

      foreach ($duePayments as $duePayment) {
           ProcessInstallment::dispatch($duePayment);
      }

    //   $dueInstallments = InstallmentSchedule::whereDate('due_date', Carbon::today())
    //                             ->whereNull('paid_date')->get();
    //   foreach ($dueInstallments as $due) {
    //     $orderId = $due->order_id;
    //     $installmentId = $due->id;

    //     Mail::to($order->email)->send(new InstallmentDueMail($orderId, $installmentId));
    //   }
        return 0;
    }
}
