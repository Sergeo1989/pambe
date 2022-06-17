<?php

namespace App\Entity;

use App\Repository\NeedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NeedRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Need
{ 
    public const DISABLED = 0;
    public const PENDING = 1;
    public const CONFIRMED = 2;
    public const REJECTED = 3;
    public const PUBLISHED = 4;
    public const EXPIRED = 5;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="needs")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $document;

    /**
     * @Vich\UploadableField(mapping="need_files", fileNameProperty="document")
     * @var File
     */
    private $documentFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_add;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_upd;

    /**
     * @ORM\OneToMany(targetEntity=Proposal::class, mappedBy="need", cascade={"persist", "remove"})
     */
    private $proposals;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delay;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryProfessional::class, inversedBy="needs")
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    public function setDocumentFile(File $documentFile = null)
    {
        $this->documentFile = $documentFile;

        if ($documentFile) {
            $this->date_upd = new \DateTime('now');
        }
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(?\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

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
        $this->nature = self::PENDING;
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

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(?\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

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
            $proposal->setNeed($this);
        }

        return $this;
    }

    public function removeProposal(Proposal $proposal): self
    {
        if ($this->proposals->removeElement($proposal)) {
            // set the owning side to null (unless already changed)
            if ($proposal->getNeed() === $this) {
                $proposal->setNeed(null);
            }
        }

        return $this;
    }

    public function isAlreadyProposed(Professional $professional): bool 
    {
        foreach ($this->proposals as $proposal) 
            if ($proposal->getProfessional() === $professional) return true;
        return false;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function getCategory(): ?CategoryProfessional
    {
        return $this->category;
    }

    public function setCategory(?CategoryProfessional $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getNature(): ?int
    {
        return $this->nature;
    }

    public function setNature(?int $nature): self
    {
        $this->nature = $nature;

        return $this;
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
