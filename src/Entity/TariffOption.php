<?php

namespace App\Entity;

use App\Repository\TariffOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TariffOptionRepository::class)
 * @UniqueEntity("title")
 * @ORM\HasLifecycleCallbacks()
 */
class TariffOption
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=TariffTariffOption::class, mappedBy="tariffOption")
     */
    private $tariffTariffOptions;

    public function __construct()
    {
        $this->tariffTariffOptions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $tariffTariffOption->setTariffOption($this);
        }

        return $this;
    }

    public function removeTariffTariffOption(TariffTariffOption $tariffTariffOption): self
    {
        if ($this->tariffTariffOptions->removeElement($tariffTariffOption)) {
            // set the owning side to null (unless already changed)
            if ($tariffTariffOption->getTariffOption() === $this) {
                $tariffTariffOption->setTariffOption(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->position = 0;
    }
}
