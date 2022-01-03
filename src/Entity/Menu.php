<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Menu
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="menus")
     */
    private $menu;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="menu")
     */
    private $menus;

    /**
     * @ORM\OneToMany(targetEntity=CategoryProfessional::class, mappedBy="menu")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $children;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;


    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
 
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMenu(): ?self
    {
        return $this->menu;
    }

    public function setMenu(?self $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(self $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setMenu($this);
        }

        return $this;
    }

    public function removeMenu(self $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getMenu() === $this) {
                $menu->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CategoryProfessional[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategoryProfessional $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setMenu($this);
        }

        return $this;
    }

    public function removeCategory(CategoryProfessional $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getMenu() === $this) {
                $category->setMenu(null);
            }
        }

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getChildren(): ?bool
    {
        return $this->children;
    }

    public function setChildren(?bool $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->children = false;
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
}
