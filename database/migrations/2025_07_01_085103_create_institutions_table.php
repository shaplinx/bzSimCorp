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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. SMAN1
            $table->string('name');           // e.g. State Senior High School 1
            $table->string("reset_sn_period");// number followed by perioid code of d, m, y (e.g. 1d represent every one day)
            $table->string("sn_template"); //// e.g. [SN]/[INSTANCE]/[CLASSIFICATION]/[MONTH_ROMAN]/[YEAR]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
