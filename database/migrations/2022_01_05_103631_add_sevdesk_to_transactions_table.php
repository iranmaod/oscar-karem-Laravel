<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSevdeskToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('amount')->after('transaction_id')->default(0);
            $table->string('date')->after('amount')->nullable();
            $table->string('sevdesk_transaction_id')->after('date')->nullable();
            $table->integer('order_id')->after('sevdesk_transaction_id');
            $table->integer('payment_id')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
          $table->dropColumn('sevdesk_transaction_id');
          $table->dropColumn('date');
          $table->dropColumn('amount');
          $table->dropColumn('order_id');
          $table->dropColumn('payment_id');
        });
    }
}
