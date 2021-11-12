<?php

namespace App\Entity;

use App\Repository\SocialMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialMediaRepository::class)
 * @Vich\Uploadable
 */
class SocialMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $icon;

    /**
     * @Vich\UploadableField(mapping="social_media_images", fileNameProperty="icon")
     * @var File
     */
    private $iconFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $date_upd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Professional::class, mappedBy="social_medias")
     */
    private $professionals;

    public function __construct()
    {
        $this->professionals = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
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

    public function getDateUpd(): ?\DateTimeInterface
    {
        return $this->date_upd;
    }

    public function setDateUpd(\DateTimeInterface $date_upd): self
    {
        $this->date_upd = $date_upd;

        return $this;
    }

    public function setIconFile(File $iconFile = null)
    {
        $this->iconFile = $iconFile;

        if ($iconFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->date_upd = new \DateTime('now');
        }
    }

    public function getIconFile()
    {
        return $this->iconFile;
    }

    /**
     * @return Collection|Professional[]
     */
    public function getProfessionals(): Collection
    {
        return $this->professionals;
    }

    public function addProfessional(Professional $professional): self
    {
        if (!$this->professionals->contains($professional)) {
            $this->professionals[] = $professional;
            $professional->addSocialMedia($this);
        }

        return $this;
    }

    public function removeProfessional(Professional $professional): self
    {
        if ($this->professionals->removeElement($professional)) {
            $professional->removeSocialMedia($this);
        }

        return $this;
    }
}
