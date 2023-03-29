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
        Schema::create('call_log_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cid')->unsigned();
            $table->foreign('cid')->references('id')->on('call_log_creations')->onDelete("cascade")->onUpdate("NO ACTION");
            $table->date("date")->nullable();
            $table->string('randomno');
            $table->string("refrenceno")->default('');
            $table->string("med_refrenceno")->default('');
            $table->string("from")->defalt('');
            $table->string('to')->default('');
            $table->string('subject')->default('');
            $table->string('medium')->default('');
            $table->string('comfile')->default('');
            $table->string('filetype')->default('');
            $table->integer('createdby_userid');
            $table->integer('updatedby_userid')->nullable()->default(null);
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
        Schema::dropIfExists('call_log_files');
    }
};
