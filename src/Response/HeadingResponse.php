<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class HeadingResponse implements \JsonSerializable
{
    private Entity\Heading $heading;
    /** @var \App\Entity\Task[] */
    private ?iterable $tasks;

    public function __construct(Entity\Heading $heading, ?iterable $tasks = null)
    {
        $this->heading = $heading;
        $this->tasks = $tasks;
    }

    public function jsonSerialize()
    {
        $return = [
            'id' => (string) $this->heading->getId(),
            'title' => $this->heading->getTitle(),
            'created_at' => $this->heading->getCreatedAt()->format(\DateTimeInterface::RFC3339),
        ];
        if ($this->tasks !== null && !empty($this->tasks)) {
            $return['tasks'] = [];
            foreach ($this->tasks as $task) {
                $return['tasks'][] = new TaskResponse($task);
            }
        }
        return $return;
    }
}
