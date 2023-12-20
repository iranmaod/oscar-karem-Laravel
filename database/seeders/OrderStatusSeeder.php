<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      OrderStatus::insert([
        array(
          'id' => 1,
          'name' => 'Generated',
          'slug' => 'generated',
        ),
        array(
          'id' => 2,
          'name' => 'Active',
          'slug' => 'active',
        ),
        array(
          'id' => 3,
          'name' => 'Pending',
          'slug' => 'pending',
        ),
        array(
          'id' => 4,
          'name' => 'On hold',
          'slug' => 'hold',
        ),
        array(
          'id' => 5,
          'name' => 'Cancelled',
          'slug' => 'cancelled',
        ),
        array(
          'id' => 6,
          'name' => 'Paid',
          'slug' => 'paid',
        )
      ]);
    }
}
