<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime("date");
            $table->string("name");
            $table->text("note")->nullable();
            $table->enum("type",["in","out"]);
            $table->unsignedBigInteger("transaction_category_id");
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories')->onDelete('cascade')->onUpdate("cascade");
            $table->string('bank_id');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
