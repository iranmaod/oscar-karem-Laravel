<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      PaymentMethod::insert([
        array(
          'id' => 1,
          'name' => 'Abilita Pay - Purchase On Account B2B',
          'slug' => 'abilita-kar_b2b',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ),
        array(
          'id' => 2,
          'name' => 'Abilita Pay - Purchase On Account',
          'slug' => 'abilita-kar',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ),
        array(
          'id' => 3,
          'name' => 'Abilita Pay - Paypal',
          'slug' => 'abilita-paypal',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ),
        array(
          'id' => 4,
          'name' => 'Abilita Pay - Sepa Direct Debit (B2B)',
          'slug' => 'abilita-dd_b2b',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ),
        array(
          'id' => 5,
          'name' => 'Abilita Pay - Sepa Direct Debit',
          'slug' => 'abilita-dd',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        ),
        array(
          'id' => 6,
          'name' => 'Abilita Pay - Credit Card',
          'slug' => 'abilita-cc',
          'status_id' => 3,
          'created_at' => date("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s")
        )
      ]);
    }
}
