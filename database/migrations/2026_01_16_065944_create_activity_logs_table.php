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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('action_type', ['created', 'updated', 'deleted', 'generated', 'uploaded', 'downloaded']);
            $table->string('module'); // 'products_virtual', 'prompts', 'images', 'batch', etc.
            $table->text('description');
            $table->json('metadata')->nullable(); // Additional data like prompt text, settings
            $table->string('thumbnail_path')->nullable(); // Path to result/preview image
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related entity (job, prompt, image)
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['user_id', 'created_at']);
            $table->index('module');
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
