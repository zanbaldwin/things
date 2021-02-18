<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class AreaResponse implements \JsonSerializable
{
    private Entity\Area $area;
    /** @var \App\Entity\Project[] */
    private ?iterable $projects;

    public function __construct(Entity\Area $area, ?iterable $projects = null)
    {
        $this->area = $area;
        $this->projects = $projects;
    }

    public function jsonSerialize()
    {
        $return = [
            'id' => (string) $this->area->getId(),
            'title' => $this->area->getTitle(),
            'created_at' => $this->area->getCreatedAt()->format(\DateTimeInterface::RFC3339),
        ];
        if ($this->projects !== null) {
            $return['projects'] = [];
            foreach ($this->projects as $project) {
                $return['projects'][] = new ProjectResponse($project);
            }
        }
        return $return;
    }
}
