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
        // Create whatsapp_groups table
        Schema::create('whatsapp_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // SQLite doesn't support enum, use string instead
            $table->json('filters')->nullable(); // For storing filters like class_id, phone numbers
            $table->integer('member_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });

        // Add group_id column to whatsapp_alerts if not exists
        if (Schema::hasTable('whatsapp_alerts')) {
            Schema::table('whatsapp_alerts', function (Blueprint $table) {
                if (!Schema::hasColumn('whatsapp_alerts', 'group_id')) {
                    $table->unsignedBigInteger('group_id')->nullable()->after('template_id');
                    $table->foreign('group_id')->references('id')->on('whatsapp_groups')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('whatsapp_alerts')) {
            Schema::table('whatsapp_alerts', function (Blueprint $table) {
                if (Schema::hasColumn('whatsapp_alerts', 'group_id')) {
                    $table->dropForeign(['group_id']);
                    $table->dropColumn('group_id');
                }
            });
        }

        Schema::dropIfExists('whatsapp_groups');
    }
};
