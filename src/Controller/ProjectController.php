<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity;
use App\Repository\HeadingRepository;
use App\Repository\ProjectRepository;
use App\Response\ProjectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController
{
    private ProjectRepository $projectRepository;
    private HeadingRepository $headingRepository;

    public function __construct(ProjectRepository $projectRepository, HeadingRepository $headingRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->headingRepository = $headingRepository;
    }

    #[Route("/area/{area}/projects", name: "get_area_projects", methods: ['GET'])]
    public function getProjectsByArea(Entity\Area $area): Response
    {
        return new JsonResponse($this->projectRepository->findAllByArea($area)->map(function (Entity\Project $project): ProjectResponse {
            return new ProjectResponse($project, $this->headingRepository->findAllByProject($project));
        })->toArray());
    }

    #[Route("/project/{project}", name: "get_project", methods: ['GET'])]
    public function getProject(Entity\Project $project): Response
    {
        return new JsonResponse(new ProjectResponse($project, $this->headingRepository->findAllByProject($project)));
    }
}
