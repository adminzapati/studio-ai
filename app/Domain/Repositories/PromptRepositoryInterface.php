<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Prompt;

/**
 * Prompt Repository Interface
 * 
 * Defines the contract for prompt data operations.
 * Part of the Domain layer in Clean Architecture.
 */
interface PromptRepositoryInterface
{
    /**
     * Get all prompts for a user
     * 
     * @param int $userId
     * @return Prompt[]
     */
    public function findByUserId(int $userId): array;

    /**
     * Get a single prompt by ID
     */
    public function findById(int $id): ?Prompt;

    /**
     * Create a new prompt
     */
    public function create(Prompt $prompt): Prompt;

    /**
     * Update an existing prompt
     */
    public function update(Prompt $prompt): Prompt;

    /**
     * Delete a prompt
     */
    public function delete(int $id): bool;

    /**
     * Get favorite prompts for a user
     * 
     * @param int $userId
     * @return Prompt[]
     */
    public function findFavoritesByUserId(int $userId): array;
}
