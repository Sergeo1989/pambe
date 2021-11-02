<?php

namespace App\Entity;

use App\Repository\CategoryProfessionalProfessionalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryProfessionalProfessionalRepository::class)
 */
class CategoryProfessionalProfessional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="categoryProfessionalProfessionals")
     */
    private $professional;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProfessional::class, inversedBy="categoryProfessionalProfessionals")
     */
    private $category_professional;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategoryProfessional(): ?CategoryProfessional
    {
        return $this->category_professional;
    }

    public function setCategoryProfessional(?CategoryProfessional $category_professional): self
    {
        $this->category_professional = $category_professional;

        return $this;
    }
}
