<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registration_car = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $places = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?Brand $type_of = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrationCar(): ?string
    {
        return $this->registration_car;
    }

    public function setRegistrationCar(?string $registration_car): self
    {
        $this->registration_car = $registration_car;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(?int $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getTypeOf(): ?Brand
    {
        return $this->type_of;
    }

    public function setTypeOf(?Brand $type_of): self
    {
        $this->type_of = $type_of;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
