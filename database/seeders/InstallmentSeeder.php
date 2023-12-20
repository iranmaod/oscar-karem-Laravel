<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('installments')->insert([

        array(
          'id' => 1,
          'name' => '1 Installment',
          'billing_threshold' => 1,
          'status_id' => 3
        ),
        array(
          'id' => 2,
          'name' => '2 installments',
          'billing_threshold' => 2,
          'status_id' => 3
        ),
        array(
          'id' => 3,
          'name' => '3 installments',
          'billing_threshold' => 3,
          'status_id' => 3
        ),
        array(
          'id' => 4,
          'name' => '5 installments',
          'billing_threshold' => 5,
          'status_id' => 3
        ),
        array(
          'id' => 5,
          'name' => '6 installments',
          'billing_threshold' => 6,
          'status_id' => 3
        ),
        array(
          'id' => 6,
          'name' => '8 installments',
          'billing_threshold' => 8,
          'status_id' => 3
        ),
        array(
          'id' => 7,
          'name' => '12 installments',
          'billing_threshold' => 12,
          'status_id' => 3
        )
      ]);
    }
}
