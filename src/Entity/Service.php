<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"service:read"}, "swagger_definition_name"="Read"},
 *      denormalizationContext={"groups"={"service:write"}, "swagger_definition_name"="Write"},
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
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Service implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"service:read", "professional:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"service:read", "service:write", "professional:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"service:read", "service:write", "professional:read"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="service")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $thumbnail;

    /**
     * @Vich\UploadableField(mapping="service_images", fileNameProperty="thumbnail")
     * @var File
     */
    private $thumbnailFile;

    /**
     * @var string|null
     * @Groups({"service:read", "professional:read"})
     */
    private $thumbnailUrl;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"service:read", "professional:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"service:read", "professional:read"})
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"service:read", "service:write", "professional:read"})
     */
    private $status = true;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"service:read", "service:write", "professional:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"service:read", "service:write", "professional:read"})
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="services")
     * @Groups({"service:read", "service:write"})
     */
    private $professional;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $description_size;

    public function __construct()
    {
        $this->media = new ArrayCollection();
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

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setService($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getService() === $this) {
                $medium->setService(null);
            }
        }

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(File $thumbnailFile = null)
    {
        $this->thumbnailFile = $thumbnailFile;

        if ($thumbnailFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->date_upd = new \DateTime('now');
        }

        return $this;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(?string $thumbnailUrl): self
    {
        $this->thumbnailUrl = $thumbnailUrl;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): self
    {
        $this->professional = $professional;

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

    public function getDescriptionSize(): ?int
    {
        return $this->description_size;
    }

    public function setDescriptionSize(?int $description_size): self
    {
        $this->description_size = $description_size;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'            => $this->getId(),
            'title'         => $this->getTitle(),
            'price'         => $this->getPrice(),
            'unit'          => $this->getUnit(),
            'thumbnail'     => $this->getThumbnail(),
            'thumbnailFile' => $this->getThumbnailFile(),
            'description'   => $this->getDescription()
        ];
    }
}
