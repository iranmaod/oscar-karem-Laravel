<?php

namespace App\Observers;

use App\Models\OrderInstallment;

class OrderInstallmentObserver
{
    /**
     * Handle the OrderInstallment "created" event.
     *
     * @param  \App\Models\OrderInstallment  $orderInstallment
     * @return void
     */
    public function created(OrderInstallment $orderInstallment)
    {
      if($orderInstallment->order_status_id == 6 && $orderInstallment->current_count < $orderInstallment->total_count){
        $installment = new OrderInstallment;
        $installment->order_id =  $orderInstallment->order_id;
        $installment->deal_id =  $orderInstallment->deal_id;
        $installment->due_date = Carbon::parse($orderInstallment->due_date)->addMonths(1)->format('Y-m-d H:i:s') : null;
        $installment->current_count = $orderInstallment->current_count + 1;
        $installment->total_count = $orderInstallment->total_count;
        $installment->order_status_id = 3;
        $installment->save();
      }
    }

    /**
     * Handle the OrderInstallment "updated" event.
     *
     * @param  \App\Models\OrderInstallment  $orderInstallment
     * @return void
     */
    public function updated(OrderInstallment $orderInstallment)
    {
        if($orderInstallment->order_status_id == 6 && $orderInstallment->current_count <  $orderInstallment->total_count){
          $installment = new OrderInstallment();
          $installment->order_id =  $orderInstallment->order_id;
          $installment->deal_id =  $orderInstallment->deal_id;
          $installment->due_date = Carbon::parse($orderInstallment->due_date)->addMonths(1)->format('Y-m-d H:i:s') : null;
          $installment->current_count = $orderInstallment->current_count + 1;
          $installment->total_count = $orderInstallment->total_count;
          $installment->order_status_id = 3;
          $installment->save();
        }
    }

    /**
     * Handle the OrderInstallment "deleted" event.
     *
     * @param  \App\Models\OrderInstallment  $orderInstallment
     * @return void
     */
    public function deleted(OrderInstallment $orderInstallment)
    {
        //
    }

    /**
     * Handle the OrderInstallment "restored" event.
     *
     * @param  \App\Models\OrderInstallment  $orderInstallment
     * @return void
     */
    public function restored(OrderInstallment $orderInstallment)
    {
        //
    }

    /**
     * Handle the OrderInstallment "force deleted" event.
     *
     * @param  \App\Models\OrderInstallment  $orderInstallment
     * @return void
     */
    public function forceDeleted(OrderInstallment $orderInstallment)
    {
        //
    }
}
