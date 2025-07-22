<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StationControllerTest extends WebTestCase
{
    public function testDetailsWithoutStationId(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/stations/details', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer super-secret-token',
        ]);

        $this->assertResponseStatusCodeSame(400);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('Missing stationId parameter', $data['error']);
    }

}
