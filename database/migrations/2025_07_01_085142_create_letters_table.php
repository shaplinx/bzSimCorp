<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('institution_id')->constrained('institutions')->onDelete('restrict');
            $table->foreignId('classification_id')->constrained('classifications')->onDelete('restrict');
            $table->unsignedInteger('sn')->nullable();
            $table->string('letter_number')->nullable();
            $table->string('subject');
            $table->boolean('public')->default(false);
            $table->string('recipient')->nullable();
            $table->date('letter_date');
            $table->string('file_path')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');


            $table->unique(['institution_id', 'classification_id', 'letter_date', 'sn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
