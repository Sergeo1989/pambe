<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"notification:read"}},
 *      denormalizationContext={"groups"={"notification:write"}},
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
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"notification:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     * @Groups({"notification:read", "notification:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"notification:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"notification:read", "notification:write"})
     */
    private $is_read;

    /**
     * @ORM\Column(type="object", nullable=true)
     * @Groups({"notification:read", "notification:write"})
     */
    private $object;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"notification:read", "notification:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"notification:read", "notification:write"})
     */
    private $content;

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

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): self
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->is_read = false;
        $this->date_add = new \DateTime("now");
    }
}
