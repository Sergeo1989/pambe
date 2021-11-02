<?php

namespace App\Entity;

use App\Repository\SocialMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialMediaRepository::class)
 */
class SocialMedia
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
    private $icon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalSocialMedia::class, mappedBy="social_media")
     */
    private $professionalSocialMedia;

    public function __construct()
    {
        $this->professionalSocialMedia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
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

    /**
     * @return Collection|ProfessionalSocialMedia[]
     */
    public function getProfessionalSocialMedia(): Collection
    {
        return $this->professionalSocialMedia;
    }

    public function addProfessionalSocialMedium(ProfessionalSocialMedia $professionalSocialMedium): self
    {
        if (!$this->professionalSocialMedia->contains($professionalSocialMedium)) {
            $this->professionalSocialMedia[] = $professionalSocialMedium;
            $professionalSocialMedium->setSocialMedia($this);
        }

        return $this;
    }

    public function removeProfessionalSocialMedium(ProfessionalSocialMedia $professionalSocialMedium): self
    {
        if ($this->professionalSocialMedia->removeElement($professionalSocialMedium)) {
            // set the owning side to null (unless already changed)
            if ($professionalSocialMedium->getSocialMedia() === $this) {
                $professionalSocialMedium->setSocialMedia(null);
            }
        }

        return $this;
    }
}
