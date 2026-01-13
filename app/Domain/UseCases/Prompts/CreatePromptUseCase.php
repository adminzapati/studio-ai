<?php

namespace App\Domain\UseCases\Prompts;

use App\Domain\Entities\Prompt;
use App\Domain\Repositories\PromptRepositoryInterface;

/**
 * Create Prompt UseCase
 * 
 * Business logic for creating a new prompt.
 * Part of the Domain layer in Clean Architecture.
 */
class CreatePromptUseCase
{
    public function __construct(
        private PromptRepositoryInterface $promptRepository
    ) {}

    /**
     * Execute the use case
     */
    public function execute(
        int $userId,
        string $name,
        string $promptText,
        ?string $category = null,
        ?array $wizardData = null
    ): Prompt {
        $prompt = new Prompt(
            id: null,
            userId: $userId,
            name: $name,
            prompt: $promptText,
            category: $category,
            isFavorite: false,
            wizardData: $wizardData,
            createdAt: new \DateTime(),
            updatedAt: new \DateTime(),
        );

        return $this->promptRepository->create($prompt);
    }
}
