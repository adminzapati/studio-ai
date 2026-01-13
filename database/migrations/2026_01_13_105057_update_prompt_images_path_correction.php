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
        // Update saved_prompts table
        \DB::statement("UPDATE saved_prompts SET image_path = REPLACE(image_path, 'prompts/', 'prompt-images/') WHERE image_path LIKE 'prompts/%'");
        
        // Update image_libraries table
        \DB::statement("UPDATE image_libraries SET path = REPLACE(path, 'prompts/', 'prompt-images/') WHERE path LIKE 'prompts/%'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert saved_prompts table
        \DB::statement("UPDATE saved_prompts SET image_path = REPLACE(image_path, 'prompt-images/', 'prompts/') WHERE image_path LIKE 'prompt-images/%'");
        
        // Revert image_libraries table
        \DB::statement("UPDATE image_libraries SET path = REPLACE(path, 'prompt-images/', 'prompts/') WHERE path LIKE 'prompt-images/%'");
    }
};
