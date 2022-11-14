<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"country:read"}},
 *      denormalizationContext={"groups"={"country:write"}},
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
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"country:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"country:read", "country:write"})
     */
    private $iso_code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"country:read", "country:write", "user:read", "professional:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Region::class, mappedBy="country")
     */
    private $regions;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="country")
     */
    private $users;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
        $this->professionals = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    public function setIsoCode(string $iso_code): self
    {
        $this->iso_code = $iso_code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Region[]
     */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): self
    {
        if (!$this->regions->contains($region)) {
            $this->regions[] = $region;
            $region->setCountry($this);
        }

        return $this;
    }

    public function removeRegion(Region $region): self
    {
        if ($this->regions->removeElement($region)) {
            // set the owning side to null (unless already changed)
            if ($region->getCountry() === $this) {
                $region->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCountry($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCountry() === $this) {
                $user->setCountry(null);
            }
        }

        return $this;
    }
}
