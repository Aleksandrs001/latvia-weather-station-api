<?php


namespace App\Controller;


use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StationController extends AbstractController
{
    #[Route('/api/stations', name: 'api_stations', methods: ['GET'])]
    public function list(StationRepository $stationRepository): JsonResponse
    {
        $stations = $stationRepository->findAll();

        $data = array_map(function ($station) {
            return [
                'Station_id' => $station->getStationId(),
                'Name' => $station->getName(),
            ];
        }, $stations);

        return $this->json($data);
    }

}
