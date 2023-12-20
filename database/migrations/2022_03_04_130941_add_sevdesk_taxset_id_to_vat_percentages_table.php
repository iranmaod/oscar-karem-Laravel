<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSevdeskTaxsetIdToVatPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_percentages', function (Blueprint $table) {
            $table->string('sevdesk_taxset_id')->after('percentage');
            $table->string('display_name')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_percentages', function (Blueprint $table) {
            $table->dropColumn('sevdesk_taxset_id');
            $table->dropColumn('display_name');
        });
    }
}
