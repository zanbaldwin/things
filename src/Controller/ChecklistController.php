<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity;
use App\Repository\ChecklistRepository;
use App\Response\ChecklistItemResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChecklistController
{
    private ChecklistRepository $checklistRepository;

    public function __construct(ChecklistRepository $checklistRepository)
    {
        $this->checklistRepository = $checklistRepository;
    }

    #[Route("/task/{task}/checklist", name: "get_checklist_by_task", methods: ['GET'])]
    public function getTasksByHeading(Entity\Task $task): Response
    {
        return new JsonResponse($this->checklistRepository->findAllByTask($task)->map(function (Entity\ChecklistItem $item): ChecklistItemResponse {
            return new ChecklistItemResponse($item);
        })->toArray());
    }

    #[Route("/checklist/item/{item}", name: "get_checklist_item", methods: ['GET'])]
    public function getChecklistItem(Entity\ChecklistItem $item): Response
    {
        return new JsonResponse(new ChecklistItemResponse($item));
    }
}
