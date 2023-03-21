<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->bigInteger('call_type_id')->unsigned();
            $table->bigInteger('business_forecast_id')->unsigned();
            $table->foreign('call_type_id')->references('id')->on('call_types')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->foreign('business_forecast_id')->references('id')->on('business_forecasts')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string('status_name');
            $table->string('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
