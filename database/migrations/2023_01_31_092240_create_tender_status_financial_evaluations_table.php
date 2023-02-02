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
        Schema::create('tender_status_financial_evaluations', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('bidid')->unsigned();
            // $table->foreign('bidid')->references('id')->on('bid_creation__creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->bigInteger('qualifiedId')->unsigned();//bidderId - id of 'tender_status_bidders' table
            $table->foreign('qualifiedId')->references('id')->on('tender_status_tech_evaluations_subs')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->bigInteger('competitorId')->unsigned();
            $table->foreign('competitorId')->references('id')->on('competitor_profile_creations')->onDelete("Restrict")->onUpdate("NO ACTION");
            $table->string('unitId');
            $table->string('pricePerUnit');
            $table->integer('created_userid');
            $table->integer('edited_userid')->nullable()->default(null);
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
        Schema::dropIfExists('tender_status_financial_evaluations');
    }
};
