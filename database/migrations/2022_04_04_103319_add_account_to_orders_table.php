<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ip_address')->after('gender')->nullable();
            $table->text('b_account')->after('gender')->nullable();
            $table->text('account')->after('gender')->nullable();
            $table->string('commission_type')->after('gender')->nullable();
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
            $table->dropColumn('b_account');
            $table->dropColumn('ip_address');
            $table->dropColumn('account');
            $table->dropColumn('commission_type');

        });
    }
}
