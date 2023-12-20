<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Payment;

class InstallmentSchedule extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'order_id',
      'payment_id',
      'installment',
      'amount',
      'due_date',
      'paid_date',
      'transaction_id'
    ];

    /**
     * Belongs to Order
     */
      public function order()
      {
          return $this->belongsTo(Order::class, 'order_id', 'id');
      }

      /**
       * Belongs to status
       */
      public function payment()
      {
          return $this->belongsTo(Payment::class, 'payment_id', 'id');
      }
}
