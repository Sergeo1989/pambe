<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"skill:read"}},
 *      denormalizationContext={"groups"={"skill:write"}},
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
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"skill:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"skill:read", "skill:write", "professional:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Professional::class, mappedBy="skill", cascade={"persist", "remove"})
     * @Groups({"skill:read", "skill:write"})
     */
    private $professional;

    public function __construct()
    {
        $this->professional = new ArrayCollection();
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

    /**
     * @return Collection|Professional[]
     */
    public function getProfessional(): Collection
    {
        return $this->professional;
    }

    public function addProfessional(Professional $professional): self
    {
        if (!$this->professional->contains($professional)) {
            $this->professional[] = $professional;
            $professional->setSkill($this);
        }

        return $this;
    }

    public function removeProfessional(Professional $professional): self
    {
        if ($this->professional->removeElement($professional)) {
            // set the owning side to null (unless already changed)
            if ($professional->getSkill() === $this) {
                $professional->setSkill(null);
            }
        }

        return $this;
    }
}
