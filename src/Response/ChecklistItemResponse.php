<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class ChecklistItemResponse implements \JsonSerializable
{
    private Entity\ChecklistItem $item;

    public function __construct(Entity\ChecklistItem $item)
    {
        $this->item = $item;
    }

    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->item->getId(),
            'task' => (string) $this->item->getTask()->getId(),
            'description' => $this->item->getDescription(),
            'created_at' => $this->item->getCreatedAt()->format(\DateTimeInterface::RFC3339),
            'completed' => $this->item->isCompleted(),
        ];
    }
}
