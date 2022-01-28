<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="admin", cascade={"persist", "remove"})
     */
    private $user;
 
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity=Testimonial::class, mappedBy="admin")
     */
    private $testimonials;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="admin")
     */
    private $articles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $manage_needs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $manage_proposals;

    public function __construct()
    {
        $this->content = new ArrayCollection();
        $this->testimonials = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return Collection|Testimonial[]
     */
    public function getTestimonials(): Collection
    {
        return $this->testimonials;
    }

    public function addTestimonial(Testimonial $testimonial): self
    {
        if (!$this->testimonials->contains($testimonial)) {
            $this->testimonials[] = $testimonial;
            $testimonial->setAdmin($this);
        }

        return $this;
    }

    public function removeTestimonial(Testimonial $testimonial): self
    {
        if ($this->testimonials->removeElement($testimonial)) {
            // set the owning side to null (unless already changed)
            if ($testimonial->getAdmin() === $this) {
                $testimonial->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAdmin($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAdmin() === $this) {
                $article->setAdmin(null);
            }
        }

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTimeInterface $date_upd): self
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

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->status = true;
        $this->date_add = new \DateTime("now");
        $this->date_upd = new \DateTime("now");
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_upd = new \DateTime("now");
    }

    public function getManageNeeds(): ?bool
    {
        return $this->manage_needs;
    }

    public function setManageNeeds(?bool $manage_needs): self
    {
        $this->manage_needs = $manage_needs;

        return $this;
    }

    public function getManageProposals(): ?bool
    {
        return $this->manage_proposals;
    }

    public function setManageProposals(?bool $manage_proposals): self
    {
        $this->manage_proposals = $manage_proposals;

        return $this;
    }
}
