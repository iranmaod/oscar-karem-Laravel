<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('hs_vid');
            $table->string('phone');
            $table->integer('product_id');
            $table->integer('installment_id');
            $table->string('address');
            $table->string('lot');
            $table->string('city');
            $table->string('country');
            $table->string('plz');
            $table->string('company_name')->nullable();
            $table->string('vat')->nullable();
            $table->string('vat_percentage_id');
            $table->integer('payment_method_id')->nullable();
            $table->string('order_status_id')->default(1);
            $table->string('order_start_date')->nullable();
            $table->string('order_end_date')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
