<?php

namespace App\Entity;

use App\Repository\TariffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TariffRepository::class)
 * @UniqueEntity("title")
 * @ORM\HasLifecycleCallbacks()
 */
class Tariff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $color;

    /**
     * @ORM\Column(type="boolean")
     */
    private $most_popular;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $amount;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=TariffTariffOption::class, mappedBy="tariff", cascade={"persist", "remove"})
     */
    private $tariffTariffOptions;

    public function __construct()
    {
        $this->tariffTariffOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMostPopular(): ?bool
    {
        return $this->most_popular;
    }

    public function setMostPopular(bool $most_popular): self
    {
        $this->most_popular = $most_popular;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|TariffTariffOption[]
     */
    public function getTariffTariffOptions(): Collection
    {
        return $this->tariffTariffOptions;
    }

    public function addTariffTariffOption(TariffTariffOption $tariffTariffOption): self
    {
        if (!$this->tariffTariffOptions->contains($tariffTariffOption)) {
            $this->tariffTariffOptions[] = $tariffTariffOption;
            $tariffTariffOption->setTariff($this);
        }

        return $this;
    }

    public function removeTariffTariffOption(TariffTariffOption $tariffTariffOption): self
    {
        if ($this->tariffTariffOptions->removeElement($tariffTariffOption)) {
            // set the owning side to null (unless already changed)
            if ($tariffTariffOption->getTariff() === $this) {
                $tariffTariffOption->setTariff(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->most_popular = false;
        $this->position = 0;
    }
}
