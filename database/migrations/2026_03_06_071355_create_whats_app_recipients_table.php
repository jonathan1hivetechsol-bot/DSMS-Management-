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
        Schema::create('whats_app_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20)->unique();
            $table->string('recipient_type'); // student, teacher, parent, admin
            $table->foreignId('recipient_id')->nullable(); // References student_id, teacher_id, user_id, etc.
            $table->string('name');
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('opt_in')->default(true); // User consent for WhatsApp messages
            $table->json('alert_preferences')->nullable(); // Types of alerts they want to receive
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->index(['phone_number', 'recipient_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_recipients');
    }
};
