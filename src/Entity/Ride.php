<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["GetRide"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(["GetRide"])]
    private ?City $start_city = null;

    #[ORM\ManyToOne]
    #[Groups(["GetRide"])]
    private ?City $arrival_city = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["GetRide"])]
    private ?int $kms = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["GetRide"])]
    private ?\DateTimeInterface $start_hour = null;


    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[Groups(["GetRide"])]
    private ?User $conducteur = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'user_ride')]
    #[Groups(["GetRide"])]
    private Collection $users;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $places_available = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartCity(): ?City
    {
        return $this->start_city;
    }

    public function setStartCity(?City $start_city): self
    {
        $this->start_city = $start_city;

        return $this;
    }

    public function getArrivalCity(): ?City
    {
        return $this->arrival_city;
    }

    public function setArrivalCity(?City $arrival_city): self
    {
        $this->arrival_city = $arrival_city;

        return $this;
    }

    public function getKms(): ?int
    {
        return $this->kms;
    }

    public function setKms(?int $kms): self
    {
        $this->kms = $kms;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->start_hour;
    }

    public function setStartHour(?\DateTimeInterface $start_hour): self
    {
        $this->start_hour = $start_hour;

        return $this;
    }


    public function getConducteur(): ?User
    {
        return $this->conducteur;
    }

    public function setConducteur(?User $conducteur): self
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getPlaceAvailable(): ?int
    {
        return $this->places_available;
    }

    public function setPlaceAvailable(?int $places_available): self
    {
        $this->places_available = $places_available;

        return $this;
    }
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserRide($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserRide($this);
        }

        return $this;
    }
}
