<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceAndDealToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('hs_deal_id')->after('agent_id')->nullable();
            $table->string('sevdesk_invoice_id')->after('hs_deal_id')->nullable();
            $table->string('sevdesk_user_id')->after('hs_deal_id')->nullable();
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
            $table->dropColumn('hs_deal_id');
            $table->dropColumn('sevdesk_invoice_id');
            $table->dropColumn('sevdesk_user_id');
        });
    }
}
