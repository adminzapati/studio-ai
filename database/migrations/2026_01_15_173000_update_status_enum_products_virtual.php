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
        // Add 'prompt_ready' to the enum list
        DB::statement("ALTER TABLE products_virtual_jobs MODIFY COLUMN status ENUM('pending', 'analyzing', 'prompt_ready', 'generating', 'completed', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum list (removing 'prompt_ready')
        // Note: This might fail if there are rows with 'prompt_ready' status
        DB::statement("ALTER TABLE products_virtual_jobs MODIFY COLUMN status ENUM('pending', 'analyzing', 'generating', 'completed', 'failed') DEFAULT 'pending'");
    }
};
