<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity;
use App\Repository\ChecklistRepository;
use App\Repository\TaskRepository;
use App\Response\TaskResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController
{
    private TaskRepository $taskRepository;
    private ChecklistRepository $checklistRepository;

    public function __construct(TaskRepository $taskRepository, ChecklistRepository $checklistRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->checklistRepository = $checklistRepository;
    }

    #[Route("/heading/{heading}/tasks", name: "get_tasks_by_heading", methods: ['GET'])]
    public function getTasksByHeading(Entity\Heading $heading): Response
    {
        return new JsonResponse($this->taskRepository->findAllByHeading($heading)->map(function (Entity\Task $task): TaskResponse {
            return new TaskResponse($task, $this->checklistRepository->findAllByTask($task));
        })->toArray());
    }

    #[Route("/task/{task}", name: "get_task", methods: ['GET'])]
    public function getTask(Entity\Task $task): Response
    {
        return new JsonResponse(new TaskResponse($task, $this->checklistRepository->findAllByTask($task)));
    }
}
