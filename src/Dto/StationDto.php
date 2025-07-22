<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Station',
    description: 'Weather station',
    properties: [
        new OA\Property(property: 'Station_id', type: 'string'),
        new OA\Property(property: 'Name', type: 'string'),
    ],
    type: 'object'
)]
class StationDto
{
}

