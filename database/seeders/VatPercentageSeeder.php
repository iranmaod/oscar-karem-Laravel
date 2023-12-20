<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VatPercentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('vat_percentages')->insert([

        array(
          'id' => 1,
          'name' => 'Germany - 19%',
          'percentage' => 19,
          'status_id' =>  3
        ),
        array(
          'id' => 2,
          'name' => 'Austria - 20%',
          'percentage' => 20,
          'status_id' =>  3
        ),
        array(
          'id' => 3,
          'name' => 'Switzerland - 7,7%',
          'percentage' => 7.7,
          'status_id' =>  3
        ),
        array(
          'id' => 4,
          'name' => 'Italy - 22%',
          'percentage' => 22,
          'status_id' =>  3
        ),
        array(
          'id' => 5,
          'name' => 'Luxembourg - 17%',
          'percentage' => 17,
          'status_id' =>  3
        ),
        array(
          'id' => 6,
          'name' => 'Spain - 21%',
          'percentage' => 21,
          'status_id' =>  3
        )
      ]);
    }
}
