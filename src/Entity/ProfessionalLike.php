<?php

namespace App\Entity;

use App\Repository\ProfessionalLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalLikeRepository::class)
 */
class ProfessionalLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="likes")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="professionalLikes")
     */
    private $professional;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Professional
    {
        return $this->user;
    }

    public function setUser(?Professional $user): self
    {
        $this->user = $user;

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
