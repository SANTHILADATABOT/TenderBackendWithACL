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
        Schema::create('other_expense_subs', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->integer("mainid")->unsigned();
            $table->foreign ('mainid')->references('id')->on('other_expenses')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->enum('action',['need_call_against_expense'])->default('need_call_against_expense')->nullable();
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->foreign ('customer_id')->references('id')->on('customer_creation_profiles')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->bigInteger('call_no')->nullable();
            $table->bigInteger("expense_type_id")->unsigned();
            $table->foreign ('expense_type_id')->references('id')->on('expense_types')->restrictOnDelete()->onUpdate("NO ACTION");
            $table->decimal('amount',10,2);
            $table->text("description_sub")->nullable();
            $table->string('originalfilename')->nullable();
            $table->string('filetype')->default('');
            $table->string('filesize')->default('');
            $table->string('hasfilename')->default('');
            $table->integer('created_by');
            $table->integer('edited_by')->nullable()->default(null);
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
        Schema::dropIfExists('other_expense_subs');
    }
};
