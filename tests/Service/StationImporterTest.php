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
station_id,name,wmo_id,start_date,end_date,lat,lon,gauss1,gauss2,geogr1,geogr2,elevation,elevation_pressure
01001,Station One,12345,2000-01-01,3999-12-31,56.95,24.11,1.1,1.2,1.3,1.4,10.5,11.5
CSV;

        $tempFile = tempnam(sys_get_temp_dir(), 'station_test_');
        file_put_contents($tempFile, $csvContent);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->with('DELETE FROM App\Entity\Station')
            ->willReturn($queryMock);

        $emMock->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($station) {
                return $station instanceof Station &&
                    $station->getStationId() === '01001' &&
                    $station->getName() === 'Station One' &&
                    $station->getWmoId() === '12345';
            }));

        $emMock->expects($this->once())->method('flush');

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile);
    }

    public function testImportSkipsInvalidData(): void
    {
        $csvContent = <<<CSV
station_id,name,wmo_id,start_date,end_date,lat,lon,gauss1,gauss2,geogr1,geogr2,elevation,elevation_pressure
BADLINE
CSV;

        $tempFile = tempnam(sys_get_temp_dir(), 'station_invalid_');
        file_put_contents($tempFile, $csvContent);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $emMock->expects($this->never())->method('persist');
        $emMock->expects($this->once())->method('flush');

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile);
    }

    public function testImportThrowsIfFileMissing(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/CSV file not found/');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $importer = new StationImporter('/path/to/nonexistent/file.csv', $emMock);
        $importer->import();
    }

    public function testImportHandlesEmptyFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'station_empty_');
        file_put_contents($tempFile, '');

        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $emMock->expects($this->never())->method('persist');
        $emMock->expects($this->once())->method('flush');

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile);
    }

    public function testImportWithMultipleStations(): void
    {
        $csvContent = <<<CSV
station_id,name,wmo_id,start_date,end_date,lat,lon,gauss1,gauss2,geogr1,geogr2,elevation,elevation_pressure
01001,Station One,12345,2000-01-01,3999-12-31,56.95,24.11,1.1,1.2,1.3,1.4,10.5,11.5
01002,Station Two,12346,2001-02-02,3999-12-31,57.00,24.50,2.1,2.2,2.3,2.4,20.5,21.5
CSV;

        $tempFile = tempnam(sys_get_temp_dir(), 'station_multi_');
        file_put_contents($tempFile, $csvContent);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $emMock->expects($this->exactly(2))
            ->method('persist')
            ->with($this->isInstanceOf(Station::class));

        $emMock->expects($this->once())->method('flush');

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile);
    }

    public function testImportFailsIfFileNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/CSV file not found/');

        $em = $this->createMock(EntityManagerInterface::class);
        $importer = new StationImporter('/non/existent/file.csv', $em);
        $importer->import();
    }

    public function testImportSkipsRowWithTooFewColumns(): void
    {
        $csvContent = <<<CSV
station_id,name,wmo_id,start_date,end_date,lat,lon,gauss1,gauss2,geogr1,geogr2,elevation,elevation_pressure
01001,Incomplete
CSV;

        $tempFile = tempnam(sys_get_temp_dir(), 'station_test_');
        file_put_contents($tempFile, $csvContent);

        $emMock = $this->createMock(EntityManagerInterface::class);
        $queryMock = $this->createMock(Query::class);

        $queryMock->expects($this->once())->method('execute');

        $emMock->expects($this->once())
            ->method('createQuery')
            ->with('DELETE FROM App\Entity\Station')
            ->willReturn($queryMock);

        $emMock->expects($this->never())->method('persist');
        $emMock->expects($this->once())->method('flush');

        $importer = new StationImporter($tempFile, $emMock);
        $importer->import();

        unlink($tempFile);
        $this->addToAssertionCount(1);
    }

}
