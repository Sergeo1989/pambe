<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExchangeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Exchange implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_add;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exchanges")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="exchanges")
     */
    private $admin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity=Invite::class, inversedBy="exchanges", cascade={"persist", "remove"})
     */
    private $invite;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

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

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->is_read = false;
        $this->date_add = new \DateTime("now");
    }

    public function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'content'       => $this->content,
            'admin'         => $this->admin ? $this->admin->getId() : 0,
            'date_add'      => date_format($this->date_add, 'd-m-Y'),
        ];
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getInvite(): ?Invite
    {
        return $this->invite;
    }

    public function setInvite(?Invite $invite): self
    {
        $this->invite = $invite;

        return $this;
    }
}
