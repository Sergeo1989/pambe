<?php

namespace App\Entity;

use App\Repository\ProfessionalSocialMediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalSocialMediaRepository::class)
 */
class ProfessionalSocialMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=SocialMedia::class, inversedBy="professionalSocialMedia")
     */
    private $social_media;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="professionalSocialMedia")
     */
    private $professional;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialMedia(): ?SocialMedia
    {
        return $this->social_media;
    }

    public function setSocialMedia(?SocialMedia $social_media): self
    {
        $this->social_media = $social_media;

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
