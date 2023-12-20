<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('payment_id');
            $table->integer('installment');
            $table->float('amount')->default(0);
            $table->datetime('due_date');
            $table->datetime('paid_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installment_schedules');
    }
}
