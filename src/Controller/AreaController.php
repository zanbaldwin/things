<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity;
use App\Repository\AreaRepository;
use App\Repository\ProjectRepository;
use App\Response\AreaResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AreaController
{
    public function __construct(
        private AreaRepository $areaRepository,
        private ProjectRepository $projectRepository
    ) {
    }

    #[Route('/areas', name: 'get_areas', methods: ['GET'])]
    public function getAreas(): Response
    {
        return new JsonResponse($this->areaRepository->findAll()->map(function (Entity\Area $area): AreaResponse {
            return new AreaResponse($area, $this->projectRepository->findAllByArea($area));
        })->toArray());
    }

    #[Route('/area/{area}', name: 'get_area', methods: ['GET'])]
    public function getArea(Entity\Area $area): Response
    {
        return new JsonResponse(new AreaResponse($area, $this->projectRepository->findAllByArea($area)));
    }
}
