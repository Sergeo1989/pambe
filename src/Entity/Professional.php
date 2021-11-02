<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalRepository::class)
 */
class Professional
{
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
     * @ORM\OneToMany(targetEntity=CategoryProfessionalProfessional::class, mappedBy="professional")
     */
    private $category_professional_professionals;

    /**
     * @ORM\OneToOne(targetEntity=CategoryProfessional::class, cascade={"persist", "remove"})
     */
    private $category_professional_default;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalLanguage::class, mappedBy="professional")
     */
    private $professionalLanguages;

    /**
     * @ORM\OneToMany(targetEntity=Qualification::class, mappedBy="professional")
     */
    private $qualifications;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalSocialMedia::class, mappedBy="professional")
     */
    private $professionalSocialMedia;

    /**
     * @ORM\OneToMany(targetEntity=ProfessionalService::class, mappedBy="professional")
     */
    private $professionalServices;

    public function __construct()
    {
        $this->category_professional_professionals = new ArrayCollection();
        $this->professionalLanguages = new ArrayCollection();
        $this->qualifications = new ArrayCollection();
        $this->professionalSocialMedia = new ArrayCollection();
        $this->professionalServices = new ArrayCollection();
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
     * @return Collection|CategoryProfessionalProfessional[]
     */
    public function getCategoryProfessionalProfessionals(): Collection
    {
        return $this->category_professional_professionals;
    }

    public function addCategoryProfessionalProfessional(CategoryProfessionalProfessional $category_professional_professional): self
    {
        if (!$this->category_professional_professionals->contains($category_professional_professional)) {
            $this->category_professional_professionals[] = $category_professional_professional;
            $category_professional_professional->setProfessional($this);
        }

        return $this;
    }

    public function removeCategoryProfessionalProfessional(CategoryProfessionalProfessional $category_professional_professional): self
    {
        if ($this->category_professional_professionals->removeElement($category_professional_professional)) {
            // set the owning side to null (unless already changed)
            if ($category_professional_professional->getProfessional() === $this) {
                $category_professional_professional->setProfessional(null);
            }
        }

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
     * @return Collection|ProfessionalLanguage[]
     */
    public function getProfessionalLanguages(): Collection
    {
        return $this->professionalLanguages;
    }

    public function addProfessionalLanguage(ProfessionalLanguage $professionalLanguage): self
    {
        if (!$this->professionalLanguages->contains($professionalLanguage)) {
            $this->professionalLanguages[] = $professionalLanguage;
            $professionalLanguage->setProfessional($this);
        }

        return $this;
    }

    public function removeProfessionalLanguage(ProfessionalLanguage $professionalLanguage): self
    {
        if ($this->professionalLanguages->removeElement($professionalLanguage)) {
            // set the owning side to null (unless already changed)
            if ($professionalLanguage->getProfessional() === $this) {
                $professionalLanguage->setProfessional(null);
            }
        }

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
     * @return Collection|ProfessionalSocialMedia[]
     */
    public function getProfessionalSocialMedia(): Collection
    {
        return $this->professionalSocialMedia;
    }

    public function addProfessionalSocialMedium(ProfessionalSocialMedia $professionalSocialMedium): self
    {
        if (!$this->professionalSocialMedia->contains($professionalSocialMedium)) {
            $this->professionalSocialMedia[] = $professionalSocialMedium;
            $professionalSocialMedium->setProfessional($this);
        }

        return $this;
    }

    public function removeProfessionalSocialMedium(ProfessionalSocialMedia $professionalSocialMedium): self
    {
        if ($this->professionalSocialMedia->removeElement($professionalSocialMedium)) {
            // set the owning side to null (unless already changed)
            if ($professionalSocialMedium->getProfessional() === $this) {
                $professionalSocialMedium->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProfessionalService[]
     */
    public function getProfessionalServices(): Collection
    {
        return $this->professionalServices;
    }

    public function addProfessionalService(ProfessionalService $professionalService): self
    {
        if (!$this->professionalServices->contains($professionalService)) {
            $this->professionalServices[] = $professionalService;
            $professionalService->setProfessional($this);
        }

        return $this;
    }

    public function removeProfessionalService(ProfessionalService $professionalService): self
    {
        if ($this->professionalServices->removeElement($professionalService)) {
            // set the owning side to null (unless already changed)
            if ($professionalService->getProfessional() === $this) {
                $professionalService->setProfessional(null);
            }
        }

        return $this;
    }
}
