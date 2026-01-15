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
        Schema::create('products_virtual_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Input Images - Fal.ai Storage URLs
            $table->string('model_image_fal_url', 500)->nullable();
            $table->foreignId('model_image_library_id')->nullable()->constrained('image_libraries')->nullOnDelete();
            $table->json('product_images_fal_urls')->nullable();
            
            // Model Preset (if person detected)
            $table->foreignId('model_preset_id')->nullable()->constrained('model_presets')->nullOnDelete();
            
            // Prompt Data
            $table->text('gemini_prompt')->nullable();
            $table->text('refined_prompt')->nullable();
            
            // Generation Parameters
            $table->string('size_ratio', 20)->default('1:1');
            $table->string('background', 50)->default('auto');
            $table->enum('quality', ['low', 'medium', 'high'])->default('medium');
            $table->string('format', 10)->default('png');
            
            // Status & Result
            $table->enum('status', ['pending', 'analyzing', 'generating', 'completed', 'failed'])->default('pending');
            $table->string('result_image_path', 500)->nullable();
            $table->text('error_message')->nullable();
            
            // Metadata
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_virtual_jobs');
    }
};
