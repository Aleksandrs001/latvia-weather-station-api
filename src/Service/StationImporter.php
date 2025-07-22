<?php

namespace App\Service;

use App\Entity\Station;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class StationImporter
{
    private string $csvPath;
    private EntityManagerInterface $em;

    public function __construct(string $csvPath, EntityManagerInterface $em)
    {
        $this->csvPath = $csvPath;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function import(): void
    {
        if (!file_exists($this->csvPath)) {
            throw new \Exception("CSV file not found: {$this->csvPath}");
        }

        $handle = fopen($this->csvPath, 'r');
        if ($handle === false) {
            throw new \Exception("Unable to open CSV file.");
        }

        $this->em->createQuery('DELETE FROM App\Entity\Station')->execute();

        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 13) {
                continue;
            }
            $station = new Station();
            $station->setStationId($data[0]);
            $station->setName($data[1]);
            $station->setWmoId($data[2] );
            $station->setBeginDate( $data[3] ?? null);
            $station->setEndDate($data[4] ?? null);
            $station->setLatitude(is_numeric($data[5]) ? (float)$data[5] : null);
            $station->setLongitude(is_numeric($data[6]) ? (float)$data[6] : null);
            $station->setElevation(is_numeric($data[11]) ? (float)$data[11] : null);
            $station->setGauss1(is_numeric($data[7]) ?? null);
            $station->setGauss2(is_numeric($data[8]) ?? null);
            $station->setGeogr1(is_numeric($data[9]) ?? null);
            $station->setGeogr2(is_numeric($data[10]) ?? null);
            $station->setElevationPressure(is_numeric($data[12]) ?? null);
            $station->setCreatedAt(Carbon::now());
            $station->setUpdatedAt(Carbon::now());
            $this->em->persist($station);
        }

        fclose($handle);

        $this->em->flush();
    }

}
