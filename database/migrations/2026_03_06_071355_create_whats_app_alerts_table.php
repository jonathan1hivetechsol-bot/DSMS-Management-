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
        Schema::create('whats_app_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('whats_app_templates');
            $table->string('recipient_phone');
            $table->string('status')->default('pending'); // pending, sent, failed, delivered
            $table->text('message');
            $table->json('data')->nullable(); // Data used to populate template variables
            $table->string('provider')->default('twilio'); // twilio, meta, custom, etc.
            $table->string('provider_message_id')->nullable(); // ID from provider
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->index(['recipient_phone', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_alerts');
    }
};
