<?php

namespace App\Data\Repositories;

use App\Domain\Entities\Prompt;
use App\Domain\Repositories\PromptRepositoryInterface;
use App\Models\SavedPrompt;
use App\Data\Mappers\PromptMapper;

/**
 * Eloquent Prompt Repository
 * 
 * Implementation of PromptRepositoryInterface using Eloquent.
 * Part of the Data layer in Clean Architecture.
 */
class EloquentPromptRepository implements PromptRepositoryInterface
{
    /**
     * Get all prompts for a user
     * 
     * @param int $userId
     * @return Prompt[]
     */
    public function findByUserId(int $userId): array
    {
        $models = SavedPrompt::where('user_id', $userId)
            ->latest()
            ->get();

        return $models->map(fn($model) => PromptMapper::toEntity($model))->toArray();
    }

    /**
     * Get a single prompt by ID
     */
    public function findById(int $id): ?Prompt
    {
        $model = SavedPrompt::find($id);
        
        return $model ? PromptMapper::toEntity($model) : null;
    }

    /**
     * Create a new prompt
     */
    public function create(Prompt $prompt): Prompt
    {
        $model = SavedPrompt::create(PromptMapper::toModel($prompt));
        
        return PromptMapper::toEntity($model);
    }

    /**
     * Update an existing prompt
     */
    public function update(Prompt $prompt): Prompt
    {
        $model = SavedPrompt::findOrFail($prompt->id);
        $model->update(PromptMapper::toModel($prompt));
        
        return PromptMapper::toEntity($model->fresh());
    }

    /**
     * Delete a prompt
     */
    public function delete(int $id): bool
    {
        return SavedPrompt::destroy($id) > 0;
    }

    /**
     * Get favorite prompts for a user
     * 
     * @param int $userId
     * @return Prompt[]
     */
    public function findFavoritesByUserId(int $userId): array
    {
        $models = SavedPrompt::where('user_id', $userId)
            ->where('is_favorite', true)
            ->latest()
            ->get();

        return $models->map(fn($model) => PromptMapper::toEntity($model))->toArray();
    }
}
