<?php

namespace App\Entity;

use App\Repository\ProfessionalImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalImageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class ProfessionalImage implements \Serializable, \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="professional_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile; 

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_upd;
 
    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="galleries")
     */
    private $professional;

    /**
     * @ORM\OneToOne(targetEntity=Professional::class, inversedBy="cover")
     */
    private $pros;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $legend;

    public function __toString()
    {
        return $this->id.' : Image';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->date_upd = new \DateTime('now');
        }
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

    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    public function setProfessional(?Professional $professional): self
    {
        $this->professional = $professional;

        return $this;
    }

    public function getPros(): ?Professional
    {
        return $this->pros;
    }

    public function setPros(?Professional $pros): self
    {
        $this->pros = $pros;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->date_upd = new \DateTime("now");
    }

    public function serialize()
    {
        $this->imageFile = base64_encode($this->imageFile);
    }

    public function unserialize($serialized)
    {
        $this->imageFile = base64_decode($this->imageFile);

    }

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(?string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'legend'    => $this->getLegend(),
            'image'     => $this->getImage()
        ];
    }
}
