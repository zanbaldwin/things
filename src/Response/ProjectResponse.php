<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class ProjectResponse implements \JsonSerializable
{
    private Entity\Project $project;
    /** @var \App\Entity\Heading[] */
    private ?iterable $headings;

    public function __construct(Entity\Project $project, ?iterable $headings = null)
    {
        $this->project = $project;
        $this->headings = $headings;
    }

    public function jsonSerialize()
    {
        $return = [
            'id' => (string) $this->project->getId(),
            'area' => (string) $this->project->getArea()->getId(),
            'title' => $this->project->getTitle(),
            'created_at' => $this->project->getCreatedAt()->format(\DateTimeInterface::RFC3339),
            'notes' => $this->project->getNotes(),
            'start_date' => $this->project->hasStartDate()
                ? $this->project->getStartDate()->format(\DateTimeInterface::RFC3339)
                : null,
            'deadline' => $this->project->hasDeadline()
                ? $this->project->getDeadline()->format(\DateTimeInterface::RFC3339)
                : null,
            'completed' => $this->project->isCompleted(),
            'tags' => array_map(function (Entity\Tag $tag): TagResponse {
                return new TagResponse($tag);
            }, $this->project->getTags()->toArray()),
        ];
        if ($this->headings !== null) {
            $return['headings'] = [];
            foreach ($this->headings as $heading) {
                $return['headings'][] = new HeadingResponse($heading);
            }
        }
        return $return;
    }
}
