<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalService::class, mappedBy="service")
     */
    private $professionalServices;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="service")
     */
    private $media;

    public function __construct()
    {
        $this->professionalServices = new ArrayCollection();
        $this->media = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ProfessionalService[]
     */
    public function getProfessionalServices(): Collection
    {
        return $this->professionalServices;
    }

    public function addProfessionalService(ProfessionalService $professionalService): self
    {
        if (!$this->professionalServices->contains($professionalService)) {
            $this->professionalServices[] = $professionalService;
            $professionalService->setService($this);
        }

        return $this;
    }

    public function removeProfessionalService(ProfessionalService $professionalService): self
    {
        if ($this->professionalServices->removeElement($professionalService)) {
            // set the owning side to null (unless already changed)
            if ($professionalService->getService() === $this) {
                $professionalService->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setService($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getService() === $this) {
                $medium->setService(null);
            }
        }

        return $this;
    }
}
