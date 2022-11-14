<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"message:read"}},
 *      denormalizationContext={"groups"={"message:write"}},
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
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Message implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"message:read", "conversation:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"message:read", "message:write", "conversation:read"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"message:read", "conversation:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"message:read", "conversation:read"})
     */
    private $is_read;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @Groups({"message:write"})
     */
    private $conversation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sent")
     * @Groups({"message:read", "message:write", "conversation:read"})
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recieves")
     * @Groups({"message:read", "message:write", "conversation:read"})
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;

    public function __toString()
    {
        return $this->content;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->is_read = false;
        $this->date_add = new \DateTime("now");
        $this->day = (new \DateTime("now"))->format('Y-m-d');
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'            => $this->getId(),
            'date_add'      => $this->getDateAdd()->format('F j, Y'),
            'content'       => $this->getContent()
        ];
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
