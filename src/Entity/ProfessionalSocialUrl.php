<?php

namespace App\Entity;

use App\Repository\ProfessionalSocialUrlRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"socialurl:read"}},
 *      denormalizationContext={"groups"={"socialurl:write"}},
 *      collectionOperations={
 *          "get"={},
 *          "post"={},
 *      },
 *      itemOperations={
 *          "get"={},
 *          "put"={},
 *          "delete"={}
 *      }
 * )
 * @ORM\Entity(repositoryClass=ProfessionalSocialUrlRepository::class)
 */
class ProfessionalSocialUrl
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"socialurl:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $youtube;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $whatsapp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"socialurl:read", "socialurl:write", "professional:read"})
     */
    private $pinterest;

    /**
     * @ORM\OneToOne(targetEntity=Professional::class, mappedBy="socialUrl")
     * @Groups({"socialurl:write"})
     * This one is OK
     */
    private $professional;

    public function __toString()
    {
        return 'Média social #'.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    public function getPinterest(): ?string
    {
        return $this->pinterest;
    }

    public function setPinterest(?string $pinterest): self
    {
        $this->pinterest = $pinterest;

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
