<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Professional
{
    public const NORMAL = 0;
    public const VIP = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="professionals")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="professionals")
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="professionals")
     */
    private $city;

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
    private $verified = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_upd;

    /**
     * @ORM\OneToMany(targetEntity=Qualification::class, mappedBy="professional")
     */
    private $qualifications;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, inversedBy="professionals")
     */
    private $languages;

    /**
     * @ORM\ManyToMany(targetEntity=SocialMedia::class, inversedBy="professionals")
     */
    private $social_medias;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProfessional::class, inversedBy="professionals")
     */
    private $category_professional_default;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryProfessional::class, inversedBy="all_professionals")
     */
    private $category_professionals;

    /**
     * @ORM\OneToOne(targetEntity=ProfessionalImage::class, cascade={"persist", "remove"})
     */
    private $profil;

    /**
     * @ORM\OneToOne(targetEntity=ProfessionalImage::class, cascade={"persist", "remove"})
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $available;

    public function __construct()
    {
        $this->category_professional_professionals = new ArrayCollection();
        $this->qualifications = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->social_medias = new ArrayCollection();
        $this->category_professionals = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

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

    /**
     * @return Collection|SocialMedia[]
     */
    public function getSocialMedias(): Collection
    {
        return $this->social_medias;
    }

    public function addSocialMedia(SocialMedia $socialMedia): self
    {
        if (!$this->social_medias->contains($socialMedia)) {
            $this->social_medias[] = $socialMedia;
        }

        return $this;
    }

    public function removeSocialMedia(SocialMedia $socialMedia): self
    {
        $this->social_medias->removeElement($socialMedia);

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

    public function getProfil(): ?ProfessionalImage
    {
        return $this->profil;
    }

    public function setProfil(?ProfessionalImage $profil): self
    {
        $this->profil = $profil;

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
        $this->available = true;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
}
