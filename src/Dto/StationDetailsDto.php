<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StationDetails',
    description: 'Detailed weather station info',
    properties: [
        new OA\Property(property: 'Station_id', type: 'string'),
        new OA\Property(property: 'Name', type: 'string'),
        new OA\Property(property: 'WMO_id', type: 'string', nullable: true),
        new OA\Property(property: 'Begin_date', type: 'string', nullable: true),
        new OA\Property(property: 'End_date', type: 'string', nullable: true),
        new OA\Property(property: 'Latitude', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Longitude', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Gauss1', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Gauss2', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Geogr1', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Geogr2', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'Elevation', type: 'number', format: 'float', nullable: true),
        new OA\Property(property: 'ELEVATION_PRESSURE', type: 'number', format: 'float', nullable: true),
    ],
    type: 'object'
)]
class StationDetailsDto
{
}

