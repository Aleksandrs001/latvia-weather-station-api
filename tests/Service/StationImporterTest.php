<?php

namespace App\Tests\Service;

use App\Entity\Station;
use App\Service\StationImporter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;

class StationImporterTest extends TestCase
{
    public function testImport(): void
    {
        $csvContent = <<<CSV
01001,Station One,12345,2000-01-01,3999-12-31,56.95,24.11,1.1,1.2,1.3,1.4,10.5,11.5
CSV;

        $tempFile = tempnam(sys_get_temp_dir(), 'station_test_');
        file_put_contents($tempFile, $csvContent);

        // Моки
        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())
            ->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->with('DELETE FROM App\Entity\Station')
            ->willReturn($queryMock);

        $emMock->expects($this->once())
            ->method('flush');

        $emMock->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($station) {
                return $station instanceof Station &&
                    $station->getStationId() === '01001' &&
                    $station->getName() === 'Station One' &&
                    $station->getWmoId() === '12345';
            }));

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile); // Удалим временный файл
    }
}
