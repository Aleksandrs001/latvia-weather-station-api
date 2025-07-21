<?php


namespace App\Controller;


use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/api/stations/details', name: 'api_station_detail', methods: ['GET'])]
    public function detail(Request $request, StationRepository $stationRepository): JsonResponse
    {
        $stationId = $request->query->get('stationId');

        if (!$stationId) {
            return $this->json(['error' => 'Missing stationId parameter'], 400);
        }

        try {
            $station = $stationRepository->findOneBy(['stationId' => $stationId]);
        } catch (\Doctrine\DBAL\Exception $e) {
            return $this->json([
                'error' => 'Database is not available',
                'message' => $e->getMessage()
            ], 503);
        }


        if (!$station) {
            return $this->json(['error' => 'Missing stationId parameter'], 400);
        }

        $station = $stationRepository->findOneBy(['stationId' => $stationId]);

        if (!$station) {
            return $this->json(['error' => 'Station not found'], 404);
        }
        if ($station->getEndDate() == '3999.12.31 23:59:00'){
            $active = $station->getEndDate() . ' Active';
        } else {
            $active = $station->getEndDate() . ' Inactive';
        }

        return $this->json([
            'Station_id' => $station->getStationId(),
            'Name' => $station->getName(),
            'WMO_id' => $station->getWmoid(),
            'Begin_date' => $station->getBeginDate(),
            'End_date' => $active,
            'Latitude' => $station->getLatitude(),
            'Longitude' => $station->getLongitude(),
            'Gauss1' => $station->getGauss1(),
            'Gauss2' => $station->getGauss2(),
            'Geogr1' => $station->getGeogr1(),
            'Geogr2' => $station->getGeogr2(),
            'Elevation' => $station->getElevation(),
            'ELEVATION_PRESSURE' => $station->getElevationPressure() ?: null
        ]);
    }

}
