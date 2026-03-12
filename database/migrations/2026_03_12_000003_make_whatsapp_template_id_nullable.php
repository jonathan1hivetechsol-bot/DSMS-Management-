<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('whats_app_alerts')) {
            // For SQLite, we need to recreate the table to change column constraints
            if (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys=OFF;');
                
                // Get existing data
                $alerts = DB::table('whats_app_alerts')->get();
                
                // Drop the old table
                Schema::drop('whats_app_alerts');
                
                // Create new table with nullable template_id
                Schema::create('whats_app_alerts', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('template_id')->nullable()->constrained('whats_app_templates')->onDelete('set null');
                    $table->string('recipient_phone');
                    $table->string('status')->default('pending');
                    $table->text('message');
                    $table->json('data')->nullable();
                    $table->string('provider')->default('twilio');
                    $table->string('provider_message_id')->nullable();
                    $table->text('error_message')->nullable();
                    $table->integer('retry_count')->default(0);
                    $table->timestamp('sent_at')->nullable();
                    $table->timestamp('delivered_at')->nullable();
                    $table->timestamp('read_at')->nullable();
                    $table->unsignedBigInteger('group_id')->nullable();
                    $table->timestamps();
                    $table->index(['recipient_phone', 'status']);
                    $table->foreign('group_id')->references('id')->on('whatsapp_groups')->onDelete('set null');
                });
                
                // Restore data
                foreach ($alerts as $alert) {
                    DB::table('whats_app_alerts')->insert((array) $alert);
                }
                
                DB::statement('PRAGMA foreign_keys=ON;');
            } else {
                // For MySQL/PostgreSQL
                Schema::table('whats_app_alerts', function (Blueprint $table) {
                    $table->dropForeign(['template_id']);
                    $table->foreignId('template_id')->nullable()->constrained('whats_app_templates')->onDelete('set null')->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('whats_app_alerts')) {
            if (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('PRAGMA foreign_keys=OFF;');
                
                $alerts = DB::table('whats_app_alerts')->get();
                
                Schema::drop('whats_app_alerts');
                
                Schema::create('whats_app_alerts', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('template_id')->constrained('whats_app_templates');
                    $table->string('recipient_phone');
                    $table->string('status')->default('pending');
                    $table->text('message');
                    $table->json('data')->nullable();
                    $table->string('provider')->default('twilio');
                    $table->string('provider_message_id')->nullable();
                    $table->text('error_message')->nullable();
                    $table->integer('retry_count')->default(0);
                    $table->timestamp('sent_at')->nullable();
                    $table->timestamp('delivered_at')->nullable();
                    $table->timestamp('read_at')->nullable();
                    $table->unsignedBigInteger('group_id')->nullable();
                    $table->timestamps();
                    $table->index(['recipient_phone', 'status']);
                    $table->foreign('group_id')->references('id')->on('whatsapp_groups')->onDelete('set null');
                });
                
                foreach ($alerts as $alert) {
                    DB::table('whats_app_alerts')->insert((array) $alert);
                }
                
                DB::statement('PRAGMA foreign_keys=ON;');
            } else {
                Schema::table('whats_app_alerts', function (Blueprint $table) {
                    $table->dropForeign(['template_id']);
                    $table->foreignId('template_id')->constrained('whats_app_templates')->change();
                });
            }
        }
    }
};
