<?php

namespace App\Domain\Entities;

/**
 * Prompt Entity
 * 
 * Domain entity representing a Prompt.
 * Pure data class - no Eloquent dependency.
 */
class Prompt
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly string $name,
        public readonly string $prompt,
        public readonly ?string $category,
        public readonly ?string $imagePath,
        public readonly bool $isFavorite,
        public readonly ?array $wizardData,
        public readonly ?\DateTimeInterface $createdAt,
        public readonly ?\DateTimeInterface $updatedAt,
    ) {}

    /**
     * Create from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'],
            name: $data['name'],
            prompt: $data['prompt'],
            category: $data['category'] ?? null,
            imagePath: $data['image_path'] ?? null,
            isFavorite: $data['is_favorite'] ?? false,
            wizardData: $data['wizard_data'] ?? null,
            createdAt: isset($data['created_at']) ? new \DateTime($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new \DateTime($data['updated_at']) : null,
        );
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'prompt' => $this->prompt,
            'category' => $this->category,
            'image_path' => $this->imagePath,
            'is_favorite' => $this->isFavorite,
            'wizard_data' => $this->wizardData,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
