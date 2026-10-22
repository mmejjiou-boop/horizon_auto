<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// Table d'association : clé primaire composée (id_location, id_option)
#[ORM\Entity]
#[ORM\Table(name: 'location_option')]
class LocationOption
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'locationOptions')]
    #[ORM\JoinColumn(name: 'id_location', referencedColumnName: 'id_location', nullable: false)]
    private ?LocationVehicule $location = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_option', nullable: false)]
    private ?Option $option = null;

    // Prix de l'option au moment de la location (historique du tarif)
    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $prixApplique = null;

    public function __construct(LocationVehicule $location, Option $option, string $prixApplique)
    {
        $this->location = $location;
        $this->option = $option;
        $this->prixApplique = $prixApplique;
    }

    public function getLocation(): ?LocationVehicule
    {
        return $this->location;
    }

    public function getOption(): ?Option
    {
        return $this->option;
    }

    public function getPrixApplique(): ?string
    {
        return $this->prixApplique;
    }

    public function setPrixApplique(string $prixApplique): static
    {
        $this->prixApplique = $prixApplique;

        return $this;
    }
}
