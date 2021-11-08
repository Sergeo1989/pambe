<?php

namespace App\Entity;

use App\Repository\CategoryProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryProfessionalRepository::class)
 */
class CategoryProfessional
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=CategoryProfessionalProfessional::class, mappedBy="category_professional")
     */
    private $categoryProfessionalProfessionals;

    public function __construct()
    {
        $this->categoryProfessionalProfessionals = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|CategoryProfessionalProfessional[]
     */
    public function getCategoryProfessionalProfessionals(): Collection
    {
        return $this->categoryProfessionalProfessionals;
    }

    public function addCategoryProfessionalProfessional(CategoryProfessionalProfessional $categoryProfessionalProfessional): self
    {
        if (!$this->categoryProfessionalProfessionals->contains($categoryProfessionalProfessional)) {
            $this->categoryProfessionalProfessionals[] = $categoryProfessionalProfessional;
            $categoryProfessionalProfessional->setCategoryProfessional($this);
        }

        return $this;
    }

    public function removeCategoryProfessionalProfessional(CategoryProfessionalProfessional $categoryProfessionalProfessional): self
    {
        if ($this->categoryProfessionalProfessionals->removeElement($categoryProfessionalProfessional)) {
            // set the owning side to null (unless already changed)
            if ($categoryProfessionalProfessional->getCategoryProfessional() === $this) {
                $categoryProfessionalProfessional->setCategoryProfessional(null);
            }
        }

        return $this;
    }
}
