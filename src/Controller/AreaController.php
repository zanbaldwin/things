<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\AreaRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AreaController
{
    private AreaRepository $repository;

    public function __construct(AreaRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route("/areas", name: "get_areas")]
    public function getAreas(): Response
    {
        $areas = $this->repository->findAll();
        return new JsonResponse($areas);
    }
}
