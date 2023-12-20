<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->bigInteger('deal_id');
            $table->datetime('due_date');
            $table->datetime('paid_date')->nullable();
            $table->integer('current_count')->default(1);
            $table->integer('total_count')->default(1);
            $table->integer('payment_id')->nullable();
            $table->integer('order_status_id')->default(1);
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
        Schema::dropIfExists('order_installments');
    }
}
