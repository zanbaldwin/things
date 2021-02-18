<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class TagResponse implements \JsonSerializable
{
    private Entity\Tag $tag;

    public function __construct(Entity\Tag $tag)
    {
        $this->tag = $tag;
    }

    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->tag->getId(),
            'title' => $this->tag->getTitle(),
        ];
    }
}
