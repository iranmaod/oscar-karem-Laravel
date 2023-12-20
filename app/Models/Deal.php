<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DealInstallment;
use App\Models\Agent;

class Deal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'hs_object_id',
      'produkt',
      'art_des_deals',
      'hs_campaign',
      'pipeline',
      'createdate',
      'closedate',
      'amount',
      'comission',
      'dealname',
      'set_up_caller',
      'dealtype',
      'hs_closed_amount_in_home_currency',
      'wie_viele_ratenzahlungen_',
      'dealstage',
      'von_welcher_kampagne_',
      'hs_all_owner_ids',
      'set_up_caller',
      'hubspot_owner_id',
      'order_status',
      'number_of_installments',
      'paid_installments',
      'setter_deal_number',
      'closer_deal_number',
      'is_individual'
    ];

    /**
     * Has many Order Installments
     */
      public function order_installment()
      {
          return $this->hasMany(DealInstallment::class, 'deal_id', 'hs_object_id');
      }

      public function closer(){
        return $this->belongsTo(Agent::class, 'hubspot_owner_id', 'hs_object_id');
      }

      public function set_up_caller(){
        return $this->belongsTo(Agent::class, 'set_up_caller', 'hs_object_id');
      }

}
