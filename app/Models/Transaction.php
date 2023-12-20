<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;

class Transaction extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'transaction_id',
      'amount',
      'date',
      'sevdesk_transaction_id',
      'payment_method_slug',
      'ip_address',
      'order_id',
      'payment_id',
      'status'
    ];

    /**
     * Belongs to status
     */
      public function order()
      {
          return $this->belongsTo(Order::class);
      }

      /**
       * Belongs to status
       */
      public function payment()
      {
          return $this->belongsTo(Payment::class);
      }

      /**
       * Belongs to status
       */
      public function payment_method()
      {
          return $this->belongsTo(PaymentMethod::class, 'payment_method_slug', 'slug' );
      }
}
