<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionLead;

class CommissionLeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CommissionLead::insert([
        array(
          'id' => 1,
          'name' => 'Paid Leads - Normal',
          'slug' => 'paid_leads',
        ),
        array(
          'id' => 2,
          'name' => 'Paid Leads - Individual',
          'slug' => 'paid_leads_individual',
        ),
        array(
          'id' => 3,
          'name' => 'Follow Up Leads',
          'slug' => 'follow_up_leads',
        ),
        array(
          'id' => 4,
          'name' => 'Cold Leads - Normal',
          'slug' => 'cold_leads'
        ),
        array(
          'id' => 5,
          'name' => 'Cold Leads - Individual',
          'slug' => 'cold_leads_individual'
        )
      ]);
    }
}
