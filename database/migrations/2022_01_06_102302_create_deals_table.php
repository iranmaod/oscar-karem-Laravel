<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hs_object_id')->unique();
            $table->string('produkt')->nullable();
            $table->dateTime('createdate')->nullable();
            $table->dateTime('closedate')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('comission')->nullable();
            $table->string('dealname')->nullable();
            $table->string('dealstage')->nullable();
            $table->string('set_up_caller')->nullable();
            $table->integer('hubspot_owner_id')->nullable();
            $table->string('order_status')->nullable();
            $table->string('number_of_installments')->nullable();
            $table->string('paid_installments')->nullable();
            $table->integer('is_individual')->default(0);
            $table->integer('setter_deal_number')->default(1);
            $table->integer('closer_deal_number')->default(1);
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
        Schema::dropIfExists('deals');
    }
}
