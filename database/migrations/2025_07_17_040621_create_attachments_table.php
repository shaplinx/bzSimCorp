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
        Schema::create('messaging_attachments', function (Blueprint $table) {
            $table->id();
            $table->uuid('message_id');
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messaging_messages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
