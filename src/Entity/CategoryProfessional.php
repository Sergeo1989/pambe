<?php

namespace App\Entity;

use App\Repository\CategoryProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Action\NotFoundAction;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"catprofessional:read"}, "swagger_definition_name"="Read"},
 *      denormalizationContext={"groups"={"catprofessional:write"}, "swagger_definition_name"="Write"},
 *      collectionOperations={
 *          "get"={},
 *          "post"={
 *              "openapi_context"={
 *                  "requestBody"={
 *                      "content"={
 *                          "multipart/form-data"={
 *                              "schema"={
 *                                  "type"="object",
 *                                  "properties"={
 *                                      "name"={
 *                                          "type"="string"
 *                                      },
 *                                      "description"={
 *                                          "type"="string"
 *                                      },
 *                                      "iconFile"={
 *                                          "type"="string",
 *                                          "format"="binary"
 *                                      },
 *                                      "job"={
 *                                          "type"="string"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              },
 *          }
 *      },
 *      itemOperations={
 *          "get"={"requirements"={"id"="\d+"}},
 *          "put"={},
 *          "delete"={"requirements"={"id"="\d+"}},
 *          "edit"={
 *              "method"="POST",
 *              "path"="/category_professionals/{id}/edit",
 *              "controller"=App\Controller\Api\EmptyController::class,
 *              "openapi_context"={
 *                  "requestBody"={
 *                      "content"={
 *                          "multipart/form-data"={
 *                              "schema"={
 *                                  "type"="object",
 *                                  "properties"={
 *                                      "iconFile"={
 *                                          "type"="string",
 *                                          "format"="binary"
 *                                      }
 *                                  }
 *                              }
 *                          } 
 *                      }
 *                  }
 *               }
 *           }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"grade": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"position"})
 * @ApiFilter(BooleanFilter::class, properties={"status"})
 * @ORM\Entity(repositoryClass=CategoryProfessionalRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette catégorie de professionnel existe déjà."
 * )
 */
class CategoryProfessional
{
    public const NORMAL = 0;
    public const POPULAR = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"catprofessional:read", "professional:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"catprofessional:read", "catprofessional:write", "professional:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"catprofessional:read", "catprofessional:write"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Professional::class, mappedBy="category_professional_default")
     */
    private $professionals;

    /**
     * @ORM\ManyToMany(targetEntity=Professional::class, mappedBy="category_professionals")
     * @Groups({"catprofessional:read"})
     */
    private $all_professionals;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"catprofessional:read"})
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $view;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $icon;

    /**
     * @Vich\UploadableField(mapping="category_pro_images", fileNameProperty="icon")
     * @var File
     * @Assert\Image(
     *     minWidth = 175,
     *     maxWidth = 400,
     *     minHeight = 175,
     *     maxHeight = 400
     * )
     * @Groups({"catprofessional:write"})
     */
    private $iconFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"catprofessional:read"})
     */
    private $iconUrl;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"catprofessional:read"})
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"catprofessional:read", "catprofessional:write"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"catprofessional:read", "catprofessional:write"})
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"catprofessional:read", "catprofessional:write"})
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="categories")
     */
    private $menu;

    /**
     * @ORM\OneToMany(targetEntity=Need::class, mappedBy="category")
     */
    private $needs;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"catprofessional:read", "catprofessional:write", "professional:read"})
     */
    private $job;
    
    public function __construct()
    {
        $this->categoryProfessionalProfessionals = new ArrayCollection();
        $this->professionals = new ArrayCollection();
        $this->all_professionals = new ArrayCollection();
        $this->needs = new ArrayCollection();
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
     * @return Professional[]
     */
    public function getAllProfessionals()
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

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(?int $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->view = 0;
        $this->grade = self::NORMAL;
        $this->position = 0;
        $this->status = true;
        $this->date_upd = new \DateTime('now');
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(?string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    public function setIconFile(File $iconFile = null)
    {
        $this->iconFile = $iconFile;

        if ($iconFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->date_upd = new \DateTime('now');
        }
    }

    public function getIconFile()
    {
        return $this->iconFile;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(?int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return Collection|Need[]
     */
    public function getNeeds(): Collection
    {
        return $this->needs;
    }

    public function addNeed(Need $need): self
    {
        if (!$this->needs->contains($need)) {
            $this->needs[] = $need;
            $need->setCategory($this);
        }

        return $this;
    }

    public function removeNeed(Need $need): self
    {
        if ($this->needs->removeElement($need)) {
            // set the owning side to null (unless already changed)
            if ($need->getCategory() === $this) {
                $need->setCategory(null);
            }
        }

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }
}
