<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="cities")
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=Professional::class, mappedBy="city")
     */
    private $professionals;

    public function __construct()
    {
        $this->professionals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Professional[]
     */
    public function getProfessionals(): Collection
    {
        return $this->professionals;
    }

    public function addProfessional(Professional $professional): self
    {
        if (!$this->professionals->contains($professional)) {
            $this->professionals[] = $professional;
            $professional->setCity($this);
        }

        return $this;
    }

    public function removeProfessional(Professional $professional): self
    {
        if ($this->professionals->removeElement($professional)) {
            // set the owning side to null (unless already changed)
            if ($professional->getCity() === $this) {
                $professional->setCity(null);
            }
        }

        return $this;
    }
}
