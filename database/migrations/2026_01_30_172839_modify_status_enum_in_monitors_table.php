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
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we skip this migration
            // The column should already be created correctly in the original migration
            return;
        }
        
        // MySQL-specific syntax
        DB::statement("ALTER TABLE monitors MODIFY COLUMN status ENUM('online', 'offline', 'warning', 'maintenance', 'pending') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE monitors MODIFY COLUMN status ENUM('online', 'offline', 'warning', 'maintenance') NOT NULL DEFAULT 'online'");
    }
};
