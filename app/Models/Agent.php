<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommissionPaymentType;
use App\Models\CommissionEmployeeType;
use App\Models\Status;
use App\Models\Order;

class Agent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'hs_vid',
      'first_name',
      'last_name',
      'email',
      'user_id',
      'commission_employee_type',
      'commission_payment_type',
      'status_id'
    ];

    /**
     * Search query in multiple whereOr
     */
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('firstname', 'like', '%'.$query.'%')
                ->orWhere('lastname', 'like', '%'.$query.'%')
                ->orWhere('email', 'like', '%'.$query.'%');
    }


    /**
     * Has one Commission Payment
     */
    public function payment_type()
    {
        return $this->belongsTo(CommissionPaymentType::class, 'commission_payment_type', 'slug');
    }

    /**
     * Has one Commission Employee
     */
    public function employee_type()
    {
        return $this->belongsTo(CommissionEmployeeType::class, 'commission_employee_type', 'slug');
    }


    /**
     * Has one Commission Employee
     */
    public function order()
    {
        return $this->hasMany(Order::class, 'agent_id', 'hs_vid');
    }

    /*** Agent Name ***/

    public function getFullDetailAttribute()
    {
        return $this->first_name . ' ' . $this->last_name .' - '.$this->email;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
