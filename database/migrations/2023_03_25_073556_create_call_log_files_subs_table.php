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
        Schema::create('call_log_files_subs', function (Blueprint $table) {
            $table->id();
            $table->string('randomno');
            $table->bigInteger('mainid')->unsigned();
            $table->foreign('mainid')->references('id')->on('call_log_files')->onDelete("cascade")->onUpdate("NO ACTION");
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
        Schema::dropIfExists('call_log_files_subs');
    }
};
