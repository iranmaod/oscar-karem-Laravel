<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_percentages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('commission_employee_type');
            $table->string('commission_lead');
            $table->string('commission_payment_type');
            $table->float('first_lead', 8, 2)->default(0);
            $table->float('second_lead', 8, 2)->default(0);
            $table->float('third_lead', 8, 2)->default(0);
            $table->float('fourth_lead', 8, 2)->default(0);
            $table->float('fifth_lead', 8, 2)->default(0);
            $table->float('onward_lead', 8, 2)->default(0);
            $table->string('hs_deal_name');
            $table->integer('status_id')->default(1);
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
        Schema::dropIfExists('commission_percentages');
    }
}
