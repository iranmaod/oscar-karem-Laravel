<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\DealInstallment;
use App\Models\InstallmentSchedule;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;



class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        if($transaction->status === 'completed'){
            $this->handleOnTransactionComplete($transaction);
        } else {
            $this->handleOnTransactionPending($transaction);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
      $order = $transaction->order;
      $paymentCount = $order->transaction->where('status', 'completed')->count();
      if($transaction->status === 'completed' ){
        $this->handleOnTransactionComplete($transaction);

        if($transaction->sevdesk_transaction_id && $transaction->sevdesk_transaction_id != "" && $transaction->invoice_booked == 0){
          $sevdeskTransactionBookedId = Artisan::call('book:sevdesk_transaction', [
            'transaction' => $transaction->id, '--queue' => 'default'
          ]);

          if($sevdeskTransactionBookedId){
            $transaction->invoice_booked = 1;
            $transaction->save();
          }
        }
      } else {
          $this->handleOnTransactionPending($transaction);
      }
    }

    /**
     * Handle the Transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }


    /***
      *  Handle Transaction in case of Completed status
      */

    protected function handleOnTransactionComplete($transaction){

      $order = $transaction->order;
      $paymentCount = $order->transaction->where('status', 'completed')->count();
      $amount = $transaction->amount;
      $dueAmount = $order->due_amount - $amount;
      $paidAmount = ceil($order->total_amount) <= ceil($order->paid_amount + $amount) ? $order->total_amount : $order->paid_amount + $amount ;
      $orderTotalAmount = $order->total_amount;

     // Updates original transaction id in Payment App
     Payment::where('id', $transaction->payment_id)
            ->whereNull('original_transaction_id')
            ->update(array(
              'original_transaction_id'=> $transaction->transaction_id
            ));

     // Updates installment schedule by adding transaction id to latest record
     if($order->transaction->count() != 1){
       $emptyInstallmentSchedule = InstallmentSchedule::where('order_id', $order->id)->whereNull('paid_date')->whereNull('transaction_id')->first();
       if($emptyInstallmentSchedule){
         $emptyInstallmentSchedule->update(array('paid_date' => Carbon::now()->format('Y-m-d H:i:s'), 'transaction_id' => $transaction->transaction_id));
       }
     }


     // Updates Order status
     $orderInstallment = $order->installment->billing_threshold;
     $orderPaidInstallments = $order->installment_schedules->whereNotNull('paid_date')->count();
     $isOrderPaid = ($orderInstallment == $orderPaidInstallments);

     if($order->payment_type == 'installment'){
       $order->update(array(
         'order_status_id' => $isOrderPaid ? 6 : 2,
         'paid_amount' => $paidAmount
       ));
     } else {
       $order->update(array(
         'order_status_id' => 6,
         'paid_amount' => $paidAmount
       ));
     }



     //Create Sevdesk transactions
     if($transaction->sevdesk_transaction_id  === null){
       $sevdeskTransactionId = Artisan::call('create:sevdesk_transaction', [
         'transaction' => $transaction->id, '--queue' => 'default'
       ]);

       if($sevdeskTransactionId){
         $transaction->sevdesk_transaction_id = $sevdeskTransactionId;
       }

       $transaction->save();
     }




     // Creates Elopage Order in case it is first transactions
     if($paymentCount == 1 && !$order->elopage_order_id){
       // $elopageOrderId = Artisan::call('create:elopage_order', [
       //   'order' => $order->id, '--queue' => 'default'
       // ]);
     }

     if($paymentCount == 1 && !$order->hs_deal_id){
       // $hsDealId = Artisan::call('create:hubspot_deal', [
       //   'order' => $order->id, '--queue' => 'default'
       // ]);
     }


     if($order->payment_type == 'installment' && $paymentCount > 1){
       $dealInstallmentData = array(
        "order_id" => $transaction->order_id,
        "deal_id" => $order->hs_deal_id ?? 0,
        "paid_date" => $transaction->created_at,
        "current_count" => $order->installment_schedules->whereNotNull('paid_date')->count(),
        "total_count" => $order->installment->billing_threshold,
        "payment_id" => $transaction->payment_id
      );

      DealInstallment::create($dealInstallmentData);
     }

    }

  /***
    *  Handle Transaction in case of pending status
    */

    protected function handleOnTransactionPending($transaction){
      $order = $transaction->order;
      if($order->status->slug === 'active'){
        $order->update(array(
          'order_status_id' => 4,
        ));
      }
    }
}
