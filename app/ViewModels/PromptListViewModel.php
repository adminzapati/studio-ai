<?php

namespace App\ViewModels;

use App\Domain\Entities\Prompt;

/**
 * Prompt List ViewModel
 * 
 * Prepares data for the prompts list view.
 * Part of the Presentation layer in Clean Architecture.
 */
class PromptListViewModel
{
    /**
     * @param Prompt[] $prompts
     */
    public function __construct(
        public readonly array $prompts,
        public readonly int $currentPage,
        public readonly int $totalPages,
        public readonly ?string $filterCategory,
    ) {}

    /**
     * Create ViewModel from domain data
     * 
     * @param Prompt[] $prompts
     */
    public static function create(
        array $prompts,
        int $currentPage = 1,
        int $totalPages = 1,
        ?string $filterCategory = null
    ): self {
        return new self(
            prompts: $prompts,
            currentPage: $currentPage,
            totalPages: $totalPages,
            filterCategory: $filterCategory,
        );
    }

    /**
     * Get prompts as array for view
     */
    public function getPromptsArray(): array
    {
        return array_map(fn(Prompt $p) => $p->toArray(), $this->prompts);
    }
}
