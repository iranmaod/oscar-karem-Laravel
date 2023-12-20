<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInstallment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'order_id',
      'deal_id',
      'due_date',
      'paid_date',
      'current_count',
      'total_count',
      'payment_id',
      'order_status_id'
    ];

    /**
     * Search query in multiple whereOr
     */
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%')
                ->orWhere('email', 'like', '%'.$query.'%');
    }


    /**
     * Has many deal
     */
      public function deal()
      {
          return $this->hasMany(Deal::class, 'hs_object_id', 'deal_id');
      }
}
