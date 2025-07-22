<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $stationId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $wmo_id = null;

    #[ORM\Column(nullable: true)]
    private ?string $beginDate = null;

    #[ORM\Column(nullable: true)]
    private ?string $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $elevation = null;

    #[ORM\Column(nullable: true)]
    private ?float $gauss1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $gauss2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $geogr1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $geogr2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $elevationPressure = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStationId(): ?string
    {
        return $this->stationId;
    }

    public function setStationId(string $stationId): static
    {
        $this->stationId = $stationId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getWmoId(): ?string
    {
        return $this->wmo_id;
    }

    public function setWmoId(string $wmo_id): static
    {
        $this->wmo_id = $wmo_id;
        return $this;
    }

    public function getBeginDate()
    {
        return $this->beginDate;
    }

    public function setBeginDate($beginDate): static
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getElevation(): ?float
    {
        return $this->elevation;
    }

    public function setElevation(?float $elevation): static
    {
        $this->elevation = $elevation;
        return $this;
    }

    public function getGauss1(): ?float
    {
        return $this->gauss1;
    }

    public function setGauss1(?float $gauss1): static
    {
        $this->gauss1 = $gauss1;
        return $this;
    }

    public function getGauss2(): ?float
    {
        return $this->gauss2;
    }

    public function setGauss2(?float $gauss2): static
    {
        $this->gauss2 = $gauss2;
        return $this;
    }

    public function getGeogr1(): ?float
    {
        return $this->geogr1;
    }

    public function setGeogr1(?float $geogr1): static
    {
        $this->geogr1 = $geogr1;
        return $this;
    }

    public function getGeogr2(): ?float
    {
        return $this->geogr2;
    }

    public function setGeogr2(?float $geogr2): static
    {
        $this->geogr2 = $geogr2;
        return $this;
    }

    public function getElevationPressure(): ?float
    {
        return $this->elevationPressure;
    }

    public function setElevationPressure(?float $elevationPressure): static
    {
        $this->elevationPressure = $elevationPressure;
        return $this;
    }
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

}
