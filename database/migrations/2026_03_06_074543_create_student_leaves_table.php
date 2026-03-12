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
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('leave_type')->default('personal'); // medical, personal, casual, earned, unpaid
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('number_of_days')->default(0);
            $table->text('reason')->nullable();
            $table->text('document_path')->nullable(); // For medical certificates etc.
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->boolean('auto_attendance')->default(true); // Auto-mark attendance as "On Leave"
            $table->timestamps();
            $table->index(['student_id', 'status']);
            $table->index(['from_date', 'to_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_leaves');
    }
};
