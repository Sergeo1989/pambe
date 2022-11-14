<?php

namespace App\Entity;

use App\Repository\ProposalRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"proposal:read"}},
 *      denormalizationContext={"groups"={"proposal:write"}},
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
 * @ApiFilter(SearchFilter::class, properties={"nature": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"date_add"})
 * @ORM\Entity(repositoryClass=ProposalRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Proposal
{
    public const PENDING = 0;
    public const ACCEPTED = 2;
    public const REFUSED = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"proposal:read", "professional:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"proposal:read", "proposal:write", "professional:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"proposal:read", "proposal:write", "professional:read"})
     */
    private $note;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"proposal:read", "professional:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"proposal:read", "professional:read"})
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"proposal:read", "proposal:write", "professional:read"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="proposals")
     * @Groups({"proposal:read", "proposal:write"})
     */
    private $professional;

    /**
     * @ORM\ManyToOne(targetEntity=Need::class, inversedBy="proposals")
     * @Groups({"proposal:write", "professional:read"})
     */
    private $need;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"proposal:read", "proposal:write", "professional:read"})
     */
    private $delay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"proposal:read", "proposal:write", "professional:read"})
     */
    private $nature;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

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

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): self
    {
        $this->professional = $professional;

        return $this;
    }

    public function getNeed(): ?Need
    {
        return $this->need;
    }

    public function setNeed(?Need $need): self
    {
        $this->need = $need;

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
        $this->date_upd = new \DateTime("now");
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

    public function getNature(): ?int
    {
        return $this->nature;
    }

    public function setNature(?int $nature): self
    {
        $this->nature = $nature;

        return $this;
    }
}
