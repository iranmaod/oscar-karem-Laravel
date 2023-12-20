<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentState;

class PaymentStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      PaymentState::insert([
        array(
          'id' => 1,
          'name' => 'Paused',
          'slug' => 'paused',
          'status_id' => 3
        ),
        array(
          'id' => 2,
          'name' => 'Pending',
          'slug' => 'pending',
          'status_id' => 3
        ),
        array(
          'id' => 3,
          'name' => 'Paid',
          'slug' => 'paid',
          'status_id' => 3
        )
      ]);
    }
}
