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
        Schema::create('user_module_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('feature_module_id')->constrained()->onDelete('cascade');
            $table->boolean('is_enabled'); // Force enable (true) or disable (false)
            $table->text('reason')->nullable(); // Why override was applied
            $table->timestamps();

            $table->unique(['user_id', 'feature_module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_module_overrides');
    }
};
