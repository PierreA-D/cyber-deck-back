<?php

namespace App\Dto\Deck;

final class DeckUpsertDto
{
    /**
     * @param list<int> $cardIds
     */
    public function __construct(
        public readonly string $name,
        public readonly string $color,
        public readonly bool $isActive,
        public readonly array $cardIds,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['name']) || !is_string($data['name']) || '' === trim($data['name'])) {
            throw new \InvalidArgumentException('Field "name" is required.');
        }

        if (!isset($data['color']) || !is_string($data['color']) || '' === trim($data['color'])) {
            throw new \InvalidArgumentException('Field "color" is required.');
        }

        if (!isset($data['cardIds']) || !is_array($data['cardIds']) || [] === $data['cardIds']) {
            throw new \InvalidArgumentException('Field "cardIds" must be a non-empty array of integers.');
        }

        $cardIds = [];
        foreach ($data['cardIds'] as $cardId) {
            if (!is_int($cardId)) {
                throw new \InvalidArgumentException('Every value in "cardIds" must be an integer.');
            }

            $cardIds[] = $cardId;
        }

        $isActive = false;
        if (isset($data['isActive'])) {
            if (!is_bool($data['isActive'])) {
                throw new \InvalidArgumentException('Field "isActive" must be a boolean.');
            }

            $isActive = $data['isActive'];
        }

        return new self(
            trim($data['name']),
            trim($data['color']),
            $isActive,
            array_values(array_unique($cardIds)),
        );
    }
}
