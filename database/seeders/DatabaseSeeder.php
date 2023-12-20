<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\InstallmentSeeder;
use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UserSeeder;
//use Database\Seeders\VatPercentageSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PaymentMethodSeeder;
use Database\Seeders\CommissionPercentageSeeder;
use Database\Seeders\PaymentStateSeeder;
use Database\Seeders\CommissionLeadSeeder;
use Database\Seeders\CommissionEmployeeTypeSeeder;
use Database\Seeders\CommissionPaymentTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call([
        InstallmentSeeder::class,
        OrderStatusSeeder::class,
        StatusSeeder::class,
        UserSeeder::class,
        //VatPercentageSeeder::class,
        PaymentStateSeeder::class,
        PaymentMethodSeeder::class,
        CommissionPaymentTypeSeeder::class,
        CommissionPercentageSeeder::class,
        CommissionLeadSeeder::class,
        CommissionEmployeeTypeSeeder::class,
        CountrySeeder::class
      ]);
    }
}
