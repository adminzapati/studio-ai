<?php

namespace App\Data\Mappers;

use App\Domain\Entities\Prompt;
use App\Models\SavedPrompt;

/**
 * Prompt Mapper
 * 
 * Maps between Eloquent SavedPrompt model and Domain Prompt entity.
 * Part of the Data layer in Clean Architecture.
 */
class PromptMapper
{
    /**
     * Map Eloquent model to Domain entity
     */
    public static function toEntity(SavedPrompt $model): Prompt
    {
        return new Prompt(
            id: $model->id,
            userId: $model->user_id,
            name: $model->name,
            prompt: $model->prompt,
            category: $model->category,
            isFavorite: (bool) $model->is_favorite,
            wizardData: $model->wizard_data,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }

    /**
     * Map Domain entity to array for Eloquent model
     */
    public static function toModel(Prompt $entity): array
    {
        return [
            'user_id' => $entity->userId,
            'name' => $entity->name,
            'prompt' => $entity->prompt,
            'category' => $entity->category,
            'is_favorite' => $entity->isFavorite,
            'wizard_data' => $entity->wizardData,
        ];
    }
}
