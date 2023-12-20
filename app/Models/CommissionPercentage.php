<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Status;
use App\Models\CommissionEmployeeType;
use App\Models\CommissionPaymentType;
use App\Models\CommissionLead;

class CommissionPercentage extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'slug',
      'commission_employee_type',
      'commission_lead',
      'commission_payment_type',
      'first_lead',
      'second_lead',
      'third_lead',
      'fourth_lead',
      'fifth_lead',
      'onward_lead',
      'hs_deal_name',
      'status_id'
    ];

    /**
     * Search query in multiple whereOr
     */
    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%');
    }

    /**
     * Has one Product
     */
    public function employeeType()
    {
        return $this->belongsTo(CommissionEmployeeType::class, 'commission_employee_type', 'slug');
    }

    /**
     * Has one Product
     */
    public function leadType()
    {
        return $this->belongsTo(CommissionLead::class, 'commission_lead', 'slug');
    }

    /**
     * Has one Product
     */
    public function paymentType()
    {
        return $this->belongsTo(CommissionPaymentType::class, 'commission_payment_type', 'slug');
    }


    /**
     * Has one Product
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

}
