<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity;
use App\Repository\HeadingRepository;
use App\Repository\TaskRepository;
use App\Response\HeadingResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HeadingController
{
    private HeadingRepository $headingRepository;
    private TaskRepository $taskRepository;

    public function __construct(HeadingRepository $headingRepository, TaskRepository $taskRepository)
    {
        $this->headingRepository = $headingRepository;
        $this->taskRepository = $taskRepository;
    }

    #[Route("/project/{project}/headings", name: "get_project_headings", methods: ['GET'])]
    public function getHeadingsByProject(Entity\Project $project): Response
    {
        return new JsonResponse($this->headingRepository->findAllByProject($project)->map(function (Entity\Heading $heading): HeadingResponse {
            return new HeadingResponse($heading, $this->taskRepository->findAllByHeading($heading));
        })->toArray());
    }

    #[Route("/heading/{heading}", name: "get_heading", methods: ['GET'])]
    public function getHeading(Entity\Heading $heading): Response
    {
        return new JsonResponse(new HeadingResponse($heading, $this->taskRepository->findAllByHeading($heading)));
    }
}
