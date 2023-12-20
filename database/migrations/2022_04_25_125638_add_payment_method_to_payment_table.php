<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
          $table->string('payment_method_slug')->nullable();
          $table->datetime('start_date')->nullable();
          $table->dropColumn('next_installment_date');
          $table->dropColumn('transaction_amount');
          $table->dropColumn('payment_start_count');
          $table->dropColumn('payment_end_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
          $table->dropColumn('payment_method_slug');
          $table->dropColumn('start_date');
          $table->date('next_installment_date')->nullable();
          $table->string('transaction_amount')->default(0);
          $table->integer('payment_start_count')->default(0);
          $table->integer('payment_end_count')->default(1);
        });
    }
}
