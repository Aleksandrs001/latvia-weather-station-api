<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class StationController extends AbstractController
{
    #[Route('/api/stations', name: 'api_stations', methods: ['GET'])]
    #[OA\Get(
        path: '/api/stations',
        summary: 'Get all stations',
        security: [['bearerAuth' => []]],
        tags: ['Stations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of stations',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Station')
                )
            )
        ]
    )]
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
    #[OA\Get(
        path: '/api/stations/details',
        summary: 'Get detailed info about a station',
        security: [['bearerAuth' => []]],
        tags: ['StationDetails'],
        parameters: [
            new OA\Parameter(name: 'stationId', in: 'query', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Station details',
                content: new OA\JsonContent(ref: '#/components/schemas/StationDetails')
            ),
            new OA\Response(response: 404, description: 'Station not found'),
            new OA\Response(response: 400, description: 'Missing stationId parameter'),
        ]
    )]
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
            return $this->json(['error' => 'Station not found'], 404);
        }

        $endDate = $station->getEndDate();
        $isActive = $endDate === '3999.12.31 23:59:00' ? 'Active' : 'Inactive';

        return $this->json([
            'Station_id' => $station->getStationId(),
            'Name' => $station->getName(),
            'WMO_id' => $station->getWmoid(),
            'Begin_date' => $station->getBeginDate(),
            'End_date' => $endDate . ' ' . $isActive,
            'Latitude' => $station->getLatitude(),
            'Longitude' => $station->getLongitude(),
            'Gauss1' => $station->getGauss1(),
            'Gauss2' => $station->getGauss2(),
            'Geogr1' => $station->getGeogr1(),
            'Geogr2' => $station->getGeogr2(),
            'Elevation' => $station->getElevation(),
            'ELEVATION_PRESSURE' => $station->getElevationPressure() ?: null,
        ]);
    }
}
