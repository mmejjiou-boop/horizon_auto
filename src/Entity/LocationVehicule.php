<?php

namespace App\Entity;

use App\Repository\LocationVehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationVehiculeRepository::class)]
#[ORM\Table(name: 'location_vehicule')]
class LocationVehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_location')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFinPrevue = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRetourEffective = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantTotal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_client', nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_vehicule', nullable: false)]
    private ?Vehicule $vehicule = null;

    /**
     * @var Collection<int, LocationOption>
     */
    #[ORM\OneToMany(targetEntity: LocationOption::class, mappedBy: 'location', cascade: ['remove'])]
    private Collection $locationOptions;

    public function __construct()
    {
        $this->locationOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinPrevue(): ?\DateTimeInterface
    {
        return $this->dateFinPrevue;
    }

    public function setDateFinPrevue(\DateTimeInterface $dateFinPrevue): static
    {
        $this->dateFinPrevue = $dateFinPrevue;

        return $this;
    }

    public function getDateRetourEffective(): ?\DateTimeInterface
    {
        return $this->dateRetourEffective;
    }

    public function setDateRetourEffective(?\DateTimeInterface $dateRetourEffective): static
    {
        $this->dateRetourEffective = $dateRetourEffective;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(?string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    /**
     * @return Collection<int, LocationOption>
     */
    public function getLocationOptions(): Collection
    {
        return $this->locationOptions;
    }

    public function addLocationOption(LocationOption $locationOption): static
    {
        if (!$this->locationOptions->contains($locationOption)) {
            $this->locationOptions->add($locationOption);
        }

        return $this;
    }

    public function removeLocationOption(LocationOption $locationOption): static
    {
        $this->locationOptions->removeElement($locationOption);

        return $this;
    }
}
