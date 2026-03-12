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
        // Add columns to users table for profile enhancement
        if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar')->nullable()->after('role');
                $table->text('bio')->nullable()->after('avatar');
                $table->string('phone')->nullable()->after('bio');
                $table->string('cnic')->nullable()->unique()->after('phone');
                $table->date('date_of_birth')->nullable()->after('cnic');
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
                $table->string('city')->nullable()->after('gender');
                $table->string('country')->default('Pakistan')->after('city');
                $table->text('full_address')->nullable()->after('country');
                $table->string('emergency_contact')->nullable()->after('full_address');
                $table->string('emergency_phone')->nullable()->after('emergency_contact');
                $table->timestamp('profile_completed_at')->nullable()->after('email_verified_at');
            });
        }

        // Enhance students table
        if (!Schema::hasColumn('students', 'registration_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('registration_number')->nullable()->unique()->after('student_id');
                $table->string('roll_number')->nullable()->after('registration_number');
                $table->string('blood_group')->nullable()->after('phone');
                $table->string('guardian_cnic')->nullable()->after('parent_phone');
                $table->string('guardian_occupation')->nullable()->after('guardian_cnic');
                $table->string('guardian_phone_alt')->nullable()->after('guardian_occupation');
                $table->text('medical_conditions')->nullable()->after('guardian_phone_alt');
                $table->string('previous_school')->nullable()->after('medical_conditions');
                $table->string('admission_status')->default('active')->after('previous_school');
                $table->date('admission_date')->nullable()->after('admission_status');
                $table->text('remarks')->nullable()->after('admission_date');
            });
        }

        // Enhance teachers table
        if (!Schema::hasColumn('teachers', 'cnic')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('cnic')->nullable()->unique()->after('teacher_id');
                $table->string('phone')->nullable()->after('cnic');
                $table->text('qualifications')->nullable()->after('qualification');
                $table->string('specialization')->nullable()->after('qualifications');
                $table->integer('years_of_experience')->default(0)->after('specialization');
                $table->text('previous_schools')->nullable()->after('years_of_experience');
                $table->string('employment_status')->default('permanent')->after('previous_schools');
                $table->date('salary_review_date')->nullable()->after('employment_status');
                $table->text('teaching_approach')->nullable()->after('salary_review_date');
                $table->timestamp('document_verified_at')->nullable()->after('teaching_approach');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'bio', 'phone', 'cnic', 'date_of_birth', 
                'gender', 'city', 'country', 'full_address', 
                'emergency_contact', 'emergency_phone', 'profile_completed_at'
            ]);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number', 'roll_number', 'blood_group',
                'guardian_cnic', 'guardian_occupation', 'guardian_phone_alt',
                'medical_conditions', 'previous_school', 'admission_status',
                'admission_date', 'remarks'
            ]);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'cnic', 'phone', 'qualifications', 'specialization',
                'years_of_experience', 'previous_schools', 'employment_status',
                'salary_review_date', 'teaching_approach', 'document_verified_at'
            ]);
        });
    }
};
