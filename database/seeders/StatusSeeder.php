<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Status::insert([
        array(
          'id' => 1,
          'name' => 'Draft',
          'slug' => 'draft',
        ),
        array(
          'id' => 2,
          'name' => 'Pending',
          'slug' => 'pending',
        ),
        array(
          'id' => 3,
          'name' => 'Active',
          'slug' => 'active',
        )
      ]);
    }
}
