<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $kms = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $start_hour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $arrival_hour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKms(): ?int
    {
        return $this->kms;
    }

    public function setKms(int $kms): self
    {
        $this->kms = $kms;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->start_hour;
    }

    public function setStartHour(\DateTimeInterface $start_hour): self
    {
        $this->start_hour = $start_hour;

        return $this;
    }

    public function getArrivalHour(): ?\DateTimeInterface
    {
        return $this->arrival_hour;
    }

    public function setArrivalHour(\DateTimeInterface $arrival_hour): self
    {
        $this->arrival_hour = $arrival_hour;

        return $this;
    }
}
