<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('elopage_product_id');
            //$table->string('country_code');
            $table->integer('installment_id');
            $table->float('amount')->default(0);
            //$table->float('total_amount')->default(0);
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
        Schema::dropIfExists('product_amounts');
    }
}
