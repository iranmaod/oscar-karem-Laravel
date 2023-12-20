<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionPaymentType;

class CommissionPaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CommissionPaymentType::insert([
        array(
          'id' => 1,
          'name' => 'Salary',
          'slug' => 'salary',
        ),
        array(
          'id' => 2,
          'name' => 'Salary + Commission',
          'slug' => 'salary_commission',
        ),
        array(
          'id' => 3,
          'name' => 'Commission',
          'slug' => 'commission'
        )
      ]);
    }
}
