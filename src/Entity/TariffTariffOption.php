<?php

namespace App\Entity;

use App\Repository\TariffTariffOptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TariffTariffOptionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class TariffTariffOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Tariff::class, inversedBy="tariffTariffOptions")
     */
    private $tariff;

    /**
     * @ORM\ManyToOne(targetEntity=TariffOption::class, inversedBy="tariffTariffOptions")
     */
    private $tariffOption;

    /**
     * @ORM\Column(type="boolean")
     */
    private $available;

    public function __toString()
    {
        return $this->title ?? $this->tariffOption->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }

    public function setTariff(?Tariff $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }

    public function getTariffOption(): ?TariffOption
    {
        return $this->tariffOption;
    }

    public function setTariffOption(?TariffOption $tariffOption): self
    {
        $this->tariffOption = $tariffOption;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->available = false;
    }

    public function __get($name)
    {
        if($name == 'tariffOption') return $this->tariffOption;
    }

    public function __isset($name)
    {
        if($name == 'tariffOption') return isset($this->tariffOption);
    }

}
