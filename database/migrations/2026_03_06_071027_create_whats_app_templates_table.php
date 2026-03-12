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
        Schema::create('whats_app_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->default('general'); // general, attendance, payroll, grades, etc.
            $table->text('template');
            $table->json('variables')->nullable(); // e.g., ['{student_name}', '{date}']
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_templates');
    }
};
