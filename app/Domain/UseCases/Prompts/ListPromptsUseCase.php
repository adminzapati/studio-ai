<?php

namespace App\Domain\UseCases\Prompts;

use App\Domain\Entities\Prompt;
use App\Domain\Repositories\PromptRepositoryInterface;

/**
 * List Prompts UseCase
 * 
 * Business logic for listing user's prompts.
 * Part of the Domain layer in Clean Architecture.
 */
class ListPromptsUseCase
{
    public function __construct(
        private PromptRepositoryInterface $promptRepository
    ) {}

    /**
     * Execute the use case
     * 
     * @param int $userId
     * @return Prompt[]
     */
    public function execute(int $userId): array
    {
        return $this->promptRepository->findByUserId($userId);
    }
}
