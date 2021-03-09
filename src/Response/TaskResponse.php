<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class TaskResponse implements \JsonSerializable
{
    public function __construct(
        private Entity\Task $task,
        /** @var \App\Entity\ChecklistItem[] */
        private ?iterable $items = null
    ) {
    }

    public function jsonSerialize()
    {
        $return = [
            'id' => (string)$this->task->getId(),
            'heading' => (string)$this->task->getHeading()->getId(),
            'title' => $this->task->getTitle(),
            'created_at' => $this->task->getCreatedAt()->format(\DateTimeInterface::RFC3339),
            'notes' => $this->task->getNotes(),
            'start_date' => $this->task->hasStartDate()
                ? $this->task->getStartDate()->format(\DateTimeInterface::RFC3339)
                : null,
            'deadline' => $this->task->hasDeadline()
                ? $this->task->getDeadline()->format(\DateTimeInterface::RFC3339)
                : null,
            'completed' => $this->task->isCompleted(),
            'tags' => array_map(function (Entity\Tag $tag): TagResponse {
                return new TagResponse($tag);
            }, $this->task->getTags()->toArray()),
        ];
        if ($this->items !== null) {
            $return['checklist'] = [];
            foreach ($this->items as $item) {
                $return['checklist'][] = new ChecklistItemResponse($item);
            }
        }
        return $return;
    }
}
