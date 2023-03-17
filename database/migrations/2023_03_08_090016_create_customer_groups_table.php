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
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('state')->unsigned();
            $table->foreign('state')->references('id')->on('state_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('city')->unsigned();
            $table->foreign('city')->references('id')->on('city_masters')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('customer_sub_category')->unsigned();
            $table->foreign('customer_sub_category')->references('id')->on('customer_sub_categories')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->string('smart_city');
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
        Schema::dropIfExists('customer_groups');
    }
};
