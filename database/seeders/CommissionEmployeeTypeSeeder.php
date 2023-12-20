<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionEmployeeType;

class CommissionEmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CommissionEmployeeType::insert([
        array(
          'id' => 1,
          'name' => 'Set-up Caller',
          'slug' => 'setter',
        ),
        array(
          'id' => 2,
          'name' => 'Closer',
          'slug' => 'closer',
        )
      ]);
    }
}
