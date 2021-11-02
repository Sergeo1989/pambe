<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LanguageRepository::class)
 */
class Language
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $iso_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalLanguage::class, mappedBy="language")
     */
    private $professionalLanguages;

    public function __construct()
    {
        $this->professionalLanguages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): self
    {
        $this->iso_code = $iso_code;

        return $this;
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

    /**
     * @return Collection|ProfessionalLanguage[]
     */
    public function getProfessionalLanguages(): Collection
    {
        return $this->professionalLanguages;
    }

    public function addProfessionalLanguage(ProfessionalLanguage $professionalLanguage): self
    {
        if (!$this->professionalLanguages->contains($professionalLanguage)) {
            $this->professionalLanguages[] = $professionalLanguage;
            $professionalLanguage->setLanguage($this);
        }

        return $this;
    }

    public function removeProfessionalLanguage(ProfessionalLanguage $professionalLanguage): self
    {
        if ($this->professionalLanguages->removeElement($professionalLanguage)) {
            // set the owning side to null (unless already changed)
            if ($professionalLanguage->getLanguage() === $this) {
                $professionalLanguage->setLanguage(null);
            }
        }

        return $this;
    }
}
