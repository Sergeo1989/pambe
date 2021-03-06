<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Tchoulom\ViewCounterBundle\Model\ViewCountable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalRepository::class)
 * @ORM\Table(name="professional", indexes={@ORM\Index(columns={"description"}, flags={"fulltext"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Professional implements ViewCountable
{
    public const NORMAL = 0;
    public const VIP = 1;

    public const YOUTUBE = 0;
    public const VIMEO = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="professional", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_upd;

    /**
     * @ORM\OneToMany(targetEntity=Qualification::class, mappedBy="professional", cascade={"remove"})
     */
    private $qualifications;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="professionals")
     */
    private $languages;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProfessional::class, inversedBy="professionals")
     */
    private $category_professional_default;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryProfessional::class, inversedBy="all_professionals")
     */
    private $category_professionals;

    /**
     * @ORM\OneToOne(targetEntity=ProfessionalImage::class, mappedBy="pros", cascade={"persist", "remove"})
     */
    private $cover;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalImage::class, mappedBy="professional", cascade={"persist", "remove"})
     */
    private $galleries;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="professional", cascade={"persist", "remove"})
     */
    private $services;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_of_service;

    /**
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    protected $views = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $share;

    /**
     * @ORM\OneToOne(targetEntity=ProfessionalSocialUrl::class, inversedBy="professional")
     */
    private $socialUrl;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="professional")
     */
    private $skill;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalLike::class, mappedBy="professional", cascade={"persist", "remove"})
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoUrl;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $videoType;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="professional", cascade={"persist", "remove"})
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="professional", cascade={"persist", "remove"})
     */
    private $proposals;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile;

    /**
      * @ORM\OneToMany(targetEntity=ProfessionalView::class, mappedBy="professional", cascade={"persist", "remove"})
      */
    protected $viewCounters;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;

    public function __construct()
    {
        $this->category_professional_professionals = new ArrayCollection();
        $this->qualifications = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->category_professionals = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->professionalLikes = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->proposals = new ArrayCollection();
        $this->viewCounters = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->user->getLastname();
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

    public function getSocialUrl(): ?ProfessionalSocialUrl
    {
        return $this->socialUrl;
    }
    
    public function setSocialUrl(?ProfessionalSocialUrl $socialUrl): self
    {
        $this->socialUrl = $socialUrl;
        
        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

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

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    /**
     * @return Collection|Qualification[]
     */
    public function getQualifications(): Collection
    {
        return $this->qualifications;
    }

    public function addQualification(Qualification $qualification): self
    {
        if (!$this->qualifications->contains($qualification)) {
            $this->qualifications[] = $qualification;
            $qualification->setProfessional($this);
        }

        return $this;
    }

    public function removeQualification(Qualification $qualification): self
    {
        if ($this->qualifications->removeElement($qualification)) {
            // set the owning side to null (unless already changed)
            if ($qualification->getProfessional() === $this) {
                $qualification->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->languages->removeElement($language);

        return $this;
    }

    public function getCategoryProfessionalDefault(): ?CategoryProfessional
    {
        return $this->category_professional_default;
    }

    public function setCategoryProfessionalDefault(?CategoryProfessional $category_professional_default): self
    {
        $this->category_professional_default = $category_professional_default;

        return $this;
    }

    /**
     * @return Collection|CategoryProfessional[]
     */
    public function getCategoryProfessionals(): Collection
    {
        return $this->category_professionals;
    }

    public function addCategoryProfessional(CategoryProfessional $categoryProfessional): self
    {
        if (!$this->category_professionals->contains($categoryProfessional)) {
            $this->category_professionals[] = $categoryProfessional;
        }

        return $this;
    }

    public function removeCategoryProfessional(CategoryProfessional $categoryProfessional): self
    {
        $this->category_professionals->removeElement($categoryProfessional);

        return $this;
    }

    public function getCover(): ?ProfessionalImage
    {
        return $this->cover;
    }

    public function setCover(?ProfessionalImage $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|ProfessionalImage[]
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function addGallery(ProfessionalImage $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries[] = $gallery;
            $gallery->setProfessional($this);
        }

        return $this;
    }

    public function removeGallery(ProfessionalImage $gallery): self
    {
        if ($this->galleries->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getProfessional() === $this) {
                $gallery->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setProfessional($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getProfessional() === $this) {
                $service->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->status = true;
        $this->verified = false;
        $this->level = self::NORMAL;
        $this->position = 0;
        $this->share = 0;
        $this->nb_of_service = 6;
        $this->available = true;
        $this->date_add = new \DateTime("now");
        $this->day = (new \DateTime("now"))->format('Y-m-d');
        $this->date_upd = new \DateTime("now");
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_upd = new \DateTime("now");
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

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

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getNbOfService(): ?int
    {
        return $this->nb_of_service;
    }

    public function setNbOfService(?int $nb_of_service): self
    {
        $this->nb_of_service = $nb_of_service;

        return $this;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    public function getShare(): ?int
    {
        return $this->share;
    }

    public function setShare(?int $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function isLikedByUser(User $user): bool 
    {
        foreach ($this->likes as $like) 
            if ($like->getUser() === $user) return true;
        return false;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * @return Collection|ProfessionalLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ProfessionalLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setProfessional($this);
        }

        return $this;
    }

    public function removeLike(ProfessionalLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getProfessional() === $this) {
                $like->setProfessional(null);
            }
        }

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getVideoType(): ?int
    {
        return $this->videoType;
    }

    public function setVideoType(?int $videoType): self
    {
        $this->videoType = $videoType;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProfessional($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProfessional() === $this) {
                $review->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Proposal[]
     */
    public function getProposals(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Proposal $proposal): self
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals[] = $proposal;
            $proposal->setProfessional($this);
        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): self
    {
        if ($this->proposals->removeElement($proposal)) {
            // set the owning side to null (unless already changed)
            if ($proposal->getProfessional() === $this) {
                $proposal->setProfessional(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
      * @return Collection
      */
    public function getViewCounters()
    {
        return $this->viewCounters;
    }
    
    /**
      * @param ProfessionalView $viewCounter
      * @return $this
      */
    public function addViewCounter(ProfessionalView $viewCounter)
    {
        $this->viewCounters[] = $viewCounter;
    
        return $this;
    }
    
    /**
      * @param ProfessionalView $viewCounter
      */
    public function removeViewCounter(ProfessionalView $viewCounter)
    {
        $this->viewCounters->removeElement($viewCounter);
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): self
    {
        $this->day = $day;

        return $this;
    }
}
