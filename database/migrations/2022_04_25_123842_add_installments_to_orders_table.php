<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstallmentsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('paid_amount')->default(0);
            $table->float('installment_amount')->default(0);
            $table->float('downpayment_amount')->default(0);
            $table->float('total_amount')->default(0);
            $table->string('installment_start_date')->nullable();
            $table->enum('payment_type', ['one-time', 'installment'])->default('one-time');
            $table->enum('installment_frequency', ['monthly', 'weekly'])->default('monthly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
          $table->dropColumn('paid_amount');
          $table->dropColumn('total_amount');
          $table->dropColumn('installment_start_date');
          $table->dropColumn('installment_amount');
          $table->dropColumn('downpayment_amount');
          $table->dropColumn('installment_frequency');
          $table->dropColumn('payment_type');
        });
    }
}
