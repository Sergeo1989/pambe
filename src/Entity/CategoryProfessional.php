<?php

namespace App\Entity;

use App\Repository\CategoryProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryProfessionalRepository::class)
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette catégorie existe déjà."
 * )
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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Professional::class, mappedBy="category_professional_default")
     */
    private $professionals;

    /**
     * @ORM\ManyToMany(targetEntity=Professional::class, mappedBy="category_professionals")
     */
    private $all_professionals;

    public function __construct()
    {
        $this->categoryProfessionalProfessionals = new ArrayCollection();
        $this->professionals = new ArrayCollection();
        $this->all_professionals = new ArrayCollection();
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
     * @return Collection|Professional[]
     */
    public function getProfessionals(): Collection
    {
        return $this->professionals;
    }

    public function addProfessional(Professional $professional): self
    {
        if (!$this->professionals->contains($professional)) {
            $this->professionals[] = $professional;
            $professional->setCategoryProfessionalDefault($this);
        }

        return $this;
    }

    public function removeProfessional(Professional $professional): self
    {
        if ($this->professionals->removeElement($professional)) {
            // set the owning side to null (unless already changed)
            if ($professional->getCategoryProfessionalDefault() === $this) {
                $professional->setCategoryProfessionalDefault(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Professional[]
     */
    public function getAllProfessionals(): Collection
    {
        return $this->all_professionals;
    }

    public function addAllProfessional(Professional $allProfessional): self
    {
        if (!$this->all_professionals->contains($allProfessional)) {
            $this->all_professionals[] = $allProfessional;
            $allProfessional->addCategoryProfessional($this);
        }

        return $this;
    }

    public function removeAllProfessional(Professional $allProfessional): self
    {
        if ($this->all_professionals->removeElement($allProfessional)) {
            $allProfessional->removeCategoryProfessional($this);
        }

        return $this;
    }
}
