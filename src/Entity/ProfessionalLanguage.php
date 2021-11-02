<?php

namespace App\Entity;

use App\Repository\ProfessionalLanguageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalLanguageRepository::class)
 */
class ProfessionalLanguage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="professionalLanguages")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="professionalLanguages")
     */
    private $professional;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): self
    {
        $this->professional = $professional;

        return $this;
    }
}
