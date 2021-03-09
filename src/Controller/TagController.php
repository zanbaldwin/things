<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Response\TagResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController
{
    public function __construct(
        private TagRepository $tagRepository
    ) {
    }

    #[Route('/tags', name: 'get_tags', methods: ['GET'])]
    public function getAllTags(): Response
    {
        return new JsonResponse($this->tagRepository->findAll()->map(function (Tag $tag): TagResponse {
            return new TagResponse($tag);
        })->toArray());
    }
}
