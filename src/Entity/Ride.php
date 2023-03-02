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
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?City $start_city = null;

    #[ORM\ManyToOne]
    private ?City $arrival_city = null;

    #[ORM\Column(nullable: true)]
    private ?int $kms = null;


    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_hour = null;


    #[ORM\ManyToOne(inversedBy: 'rides')]
    private ?User $conducteur = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'user_ride')]
    private Collection $users;

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
