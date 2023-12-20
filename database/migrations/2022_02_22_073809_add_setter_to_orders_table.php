<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetterToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('lot');
            $table->dropColumn('order_start_date');
            $table->dropColumn('order_end_date');
            $table->integer('qty')->default(1)->nullable();
            $table->integer('setter_id')->nullable();
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
          $table->dropColumn('qty');
          $table->dropColumn('setter_id')->nullable();
          $table->string('lot')->nullable();
          $table->string('order_start_date')->nullable();
          $table->string('order_end_date')->nullable();
        });
    }
}
