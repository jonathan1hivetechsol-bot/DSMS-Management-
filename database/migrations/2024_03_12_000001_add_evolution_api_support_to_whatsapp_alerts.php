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
        // Update whatsapp_alerts table to support Evolution API
        if (Schema::hasTable('whatsapp_alerts')) {
            Schema::table('whatsapp_alerts', function (Blueprint $table) {
                // Add read_at column if it doesn't exist
                if (!Schema::hasColumn('whatsapp_alerts', 'read_at')) {
                    $table->timestamp('read_at')->nullable()->after('delivered_at');
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
                if (Schema::hasColumn('whatsapp_alerts', 'read_at')) {
                    $table->dropColumn('read_at');
                }
            });
        }
    }
};
