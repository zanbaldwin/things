<?php declare(strict_types=1);

namespace App\Response;

use App\Entity;

class TagResponse implements \JsonSerializable
{
    public function __construct(
        private Entity\Tag $tag
    ) {
    }

    public function jsonSerialize()
    {
        return [
            'id' => (string) $this->tag->getId(),
            'title' => $this->tag->getTitle(),
        ];
    }
}
