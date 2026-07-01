<?php

namespace App\Dto\Extension;

use App\Entity\Extension;
use App\Dto\Card\CardResponseDto;

final class ExtensionResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    public static function fromEntity(Extension $extension): self
    {
        return new self(
            $extension->getId() ?? 0,
            $extension->getName() ?? '',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
