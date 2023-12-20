<?php

namespace App\Observers;

use App\Models\Deal;
use Carbon\Carbon;

class DealObserver
{
    /**
     * Handle the Deal "created" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function created(Deal $deal)
    {
        if($deal->closedate && $deal->closedate !=""){
          $startOfMonth = Carbon::createFromFormat('Y-m-d H:i:s', $deal->closedate)->startOfMonth();
          $endOfMonth = Carbon::createFromFormat('Y-m-d H:i:s', $deal->closedate)->endOfMonth();

          if($deal->set_up_caller && $deal->set_up_caller !="" && $deal->set_up_caller != $deal->hubspot_owner_id){
            $setter = $deal->set_up_caller;
            $setter_deal_number = Deal::whereBetween('closedate', [$startOfMonth, $endOfMonth])->where(function($query) use ($setter) {
              $query->where('set_up_caller', $setter)->orWhere('hubspot_owner_id', $setter);
            })->count();

            $deal->setter_deal_number = $setter_deal_number;
          }

          if($deal->hubspot_owner_id && $deal->hubspot_owner_id !=""){
            $closer = $deal->hubspot_owner_id;
            $closer_deal_number = Deal::whereBetween('closedate', [$startOfMonth, $endOfMonth])->where(function($query) use ($closer) {
              $query->where('set_up_caller', $closer)->orWhere('hubspot_owner_id', $closer);
            })->count();

            $deal->closer_deal_number = $closer_deal_number;
          }

          $deal->save();

        }

    }

    /**
     * Handle the Deal "updated" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function updated(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "deleted" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function deleted(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "restored" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function restored(Deal $deal)
    {
        //
    }

    /**
     * Handle the Deal "force deleted" event.
     *
     * @param  \App\Models\Deal  $deal
     * @return void
     */
    public function forceDeleted(Deal $deal)
    {
        //
    }
}
