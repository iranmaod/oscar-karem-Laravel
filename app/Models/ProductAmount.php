<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Installment;
use App\Models\Product;
use App\Models\Country;

class ProductAmount extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'elopage_product_id',
      'country_code',
      'installment_id',
      'amount',
      'total_amount'
    ];

    public static function search($query, $product, $installment)
    {
        $results = empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%');

        if( $installment != ''){
          $results->where('installment_id', $installment);
        }

        if( $product != ''){
          $results->where('elopage_product_id', $product);
        }

        return $results;
    }

    /**
     * Has one Product
     */
      public function product()
      {
          return $this->belongsTo(Product::class, 'elopage_product_id', 'elopage_product_id');
      }

    /**
     * Has one Country
     */
      public function country()
      {
          return $this->belongsTo(Country::class, 'country_code', 'code');
      }

    /**
     * Installments
     */
     public function installment()
     {
       return $this->belongsTo(Installment::class, 'installment_id', 'id');
     }
}
