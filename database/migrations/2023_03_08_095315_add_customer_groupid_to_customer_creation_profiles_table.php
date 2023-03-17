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
        Schema::table('customer_creation_profiles', function (Blueprint $table) {
            
            $table->bigInteger('customer_groupid')->unsigned()->nullable()->after('smart_city');
            $table->foreign('customer_groupid')->references('id')->on('customer_groups')->restrictOnDelete()->onUpdate("NO ACTION");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_creation_profiles', function (Blueprint $table) {
            // $table->dropColumn('customer_groupid');
        });
    }
};
