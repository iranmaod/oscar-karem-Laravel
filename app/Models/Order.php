<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Agent;
use App\Models\VatPercentage;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\Installment;
use App\Models\InstallmentSchedule;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'hs_vid',
      'firstname',
      'lastname',
      'email',
      'phone',
      'address',
      'product_id',
      'installment_id',
      'qty',
      'amount',
      'city',
      'country_code',
      'plz',
      'company_name',
      'vat',
      'dob',
      'gender',
      'vat_percentage_id',
      'order_status_id',
      'payment_method_id',
      'setter_id',
      'agent_id',
      'hs_deal_id',
      'sevdesk_invoice_id',
      'sevdesk_user_id',
      'gender',
      'account',
      'b_account',
      'sm_token',
      'ip_address',
      'qty',
      'paid_amount',
      'commission_type',
      'installment_amount',
      'downpayment_amount',
      'payment_type',
      'installment_frequency',
      'installment_start_date',
      'is_manual_amount'
    ];

    /**
     * Search query in multiple whereOr
     */
    public static function search($query, $order_status = "", $installment = "", $product = "", $setup_caller = "", $closer = "", $before = "", $after = "")
    {
        $results = empty($query) ? static::query()
            : static::where('firstname', 'like', '%'.$query.'%')
                ->orWhere('email', 'like', '%'.$query.'%')
                ->orWhere('id', $query)
                ->orWhere('lastname', 'like', '%'.$query.'%')
                ->orWhere('phone', 'like', '%'.$query.'%')
                ->orWhere('plz', 'like', '%'.$query.'%')
                ->orWhere('city', 'like', '%'.$query.'%')
                ->orWhere('address', 'like', '%'.$query.'%')
                ->orWhere('company_name', 'like', '%'.$query.'%');

        if( $order_status != ''){
          $results->where('order_status_id', $order_status);
        }

        if( $installment != ''){
          $results->where('installment_id', $installment);
        }

        if( $product != ''){
          $results->where('product_id', $product);
        }

        if( $setup_caller != ''){
          $results->where('setter_id', $setup_caller);
        }

        if( $closer != ''){
          $results->where('agent_id', $closer);
        }

        if( $before != '' && $after != ''){
          $results->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $before))->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $after));
          //$results->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $before), Carbon::createFromFormat('Y-m-d', $after)]);
        } else if($before != ''){
          $results->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $before));
        } else if($after != ''){
          $results->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $after));
        } else {
          //Do nothing
        }

        return $results;
    }

    /**
     * Has one Product
     */
      public function product()
      {
          return $this->belongsTo(Product::class, 'product_id', 'elopage_product_id');
      }

    /**
     * Has one Country
     */
      public function country()
      {
          return $this->belongsTo(Country::class, 'country_code', 'code');
      }

    /**
     * Has one Payment Method
     */
      public function payment_method()
      {
          return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
      }

    /**
     * Has many Transactions
     */
      public function transaction()
      {
          return $this->hasMany(Transaction::class);
      }

    /**
     * Has many Transactions
     */
      public function payment()
      {
          return $this->hasOne(Payment::class);
      }

    /**
     * Has many Transactions
     */
      public function installment_schedules()
      {
          return $this->hasMany(InstallmentSchedule::class, 'order_id', 'id');
      }

    /**
     * Has one Order Status
     */
      public function status()
      {
          return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
      }


    /**
     * Has one Order Agent
     */
      public function setter()
      {
          return $this->belongsTo(Agent::class, 'setter_id', 'hs_vid');
      }

    /**
     * Has one Order Agent
     */
      public function agent()
      {
          return $this->belongsTo(Agent::class, 'agent_id', 'hs_vid');
      }

    /**
     * VAT Percentage
     */
      public function vat_percentage()
      {
          return $this->belongsTo(VatPercentage::class, 'vat_percentage_id', 'id');
      }

    /**
     * Installments
     */
     public function installment()
     {
       return $this->belongsTo(Installment::class, 'installment_id', 'id');
     }
}
