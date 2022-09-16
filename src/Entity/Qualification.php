<?php

namespace App\Entity;

use App\Repository\QualificationRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"qualification:read"}},
 *      denormalizationContext={"groups"={"qualification:write"}},
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
 * @ORM\Entity(repositoryClass=QualificationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Qualification implements \JsonSerializable
{
    public const QUALIFICATION = 0;
    public const EXPERIENCE = 1;
    public const CERTIFICATE = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"qualification:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $place;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $end_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"qualification:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"qualification:read"})
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"qualification:read", "qualification:write"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="qualifications")
     * @Groups({"qualification:write"})
     */
    private $professional;

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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'place'         => $this->place,
            'start_date'    => date_format($this->start_date, 'd-m-Y'),
            'end_date'      => date_format($this->end_date, 'd-m-Y'),
            'description'   => $this->description,
            'type'          => $this->type
        ];
    }
}
