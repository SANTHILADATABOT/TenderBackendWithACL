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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_role')->after('name');
            $table->foreign('user_role')->references('id')->on('roles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('phone')->unique()->after('email');
            $table->string('photo')->after('phone');
            $table->integer('createdby');
            $table->integer('updatedby')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           
            $table->dropColumn('phone');
            $table->dropColumn('photo');
            $table->dropColumn('createdby');
            $table->dropColumn('updatedby');

        });
    }
};
