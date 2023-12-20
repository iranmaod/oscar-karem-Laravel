<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentState;
use App\Models\Transaction;

class Payment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'original_transaction_id',
        'start_date',
        'payment_method_slug'
    ];

    /**
     * Belongs to an Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    /**
     * has many Transactions
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id', 'order_id');
    }

    /**
     * Search query in multiple whereOr
     */
    public static function search($query, $order_status = "", $installment = "", $product = "", $setup_caller = "", $closer = "", $before = "", $after = "")
    {
        $results = empty($query) ? static::query()
            : static::whereHas('order', function($q) use ($query){
              $q->where('firstname', 'like', '%'.$query.'%')
                  ->orWhere('email', 'like', '%'.$query.'%')
                  ->orWhere('lastname', 'like', '%'.$query.'%')
                  ->orWhere('phone', 'like', '%'.$query.'%')
                  ->orWhere('plz', 'like', '%'.$query.'%')
                  ->orWhere('city', 'like', '%'.$query.'%')
                  ->orWhere('address', 'like', '%'.$query.'%')
                  ->orWhere('company_name', 'like', '%'.$query.'%');
            });



        if( $order_status != ''){
          $results->whereHas('order', function($query) use ($order_status){
            $query->where('order_status_id', $order_status);
          });
        }

        if( $installment != ''){
          $results->whereHas('order', function($query) use ($installment){
            $query->where('installment_id', $installment);
          });
        }

        if( $product != ''){
          $results->whereHas('order', function($query) use ($product){
            $query->where('product_id', $product);
          });
        }

        if( $setup_caller != ''){
          $results->whereHas('order', function($query) use ($setup_caller){
            $query->where('setter_id', $setup_caller);
          });
        }

        if( $closer != ''){
          $results->whereHas('order', function($query) use ($closer){
            $query->where('agent_id', $closer);
          });
        }

        if( $before != '' && $after != ''){
          $results->whereHas('order', function($query) use ($before,$after){
            $query->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $before))->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $after));
          });
        } else if($before != ''){
          $results->whereHas('order', function($query) use ($before){
            $query->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $before));
          });
        } else if($after != ''){
          $results->whereHas('order', function($query) use ($after){
            $query->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $after));
          });
        } else {
          //Do nothing
        }

        return $results;
    }
}
