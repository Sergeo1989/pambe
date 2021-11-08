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
     * @ORM\OneToMany(targetEntity=ProfessionalSocialMedia::class, mappedBy="social_media")
     */
    private $professionalSocialMedia;

    public function __construct()
    {
        $this->professionalSocialMedia = new ArrayCollection();
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
            $professionalSocialMedium->setSocialMedia($this);
        }

        return $this;
    }

    public function removeProfessionalSocialMedium(ProfessionalSocialMedia $professionalSocialMedium): self
    {
        if ($this->professionalSocialMedia->removeElement($professionalSocialMedium)) {
            // set the owning side to null (unless already changed)
            if ($professionalSocialMedium->getSocialMedia() === $this) {
                $professionalSocialMedium->setSocialMedia(null);
            }
        }

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
}
