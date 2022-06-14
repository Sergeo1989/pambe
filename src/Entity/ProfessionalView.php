<?php

namespace App\Entity;

use App\Repository\ProfessionalViewRepository;
use Doctrine\ORM\Mapping as ORM;
use Tchoulom\ViewCounterBundle\Entity\ViewCounter;
use Tchoulom\ViewCounterBundle\Model\ViewCountable;
use Tchoulom\ViewCounterBundle\Entity\ViewCounterInterface;

/**
 * @ORM\Entity(repositoryClass=ProfessionalViewRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ProfessionalView extends ViewCounter
{
    /**
     * @ORM\ManyToOne(targetEntity="Professional", cascade={"persist"}, inversedBy="viewCounters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $professional;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $day;

    /**
     * @return ViewCountable
     */
    public function getPage(): ViewCountable
    {
        return $this->professional;
    }

    /**
     * @param ViewCountable $professional
     * @return ViewCounterInterface
     */
    public function setPage(ViewCountable $professional): ViewCounterInterface
    {
        $this->professional = $professional;
    
        return $this;
    }

    /**
     * @return Professional
     */
    public function getProfessional()
    {
        return $this->professional;
    }
    
    /**
     * @param Professional $professional
     * @return $this
     */
    public function setProfessional(Professional $professional)
    {
        $this->professional = $professional;
    
        return $this;
    }

    public function __get($name)
    {
        if($name == 'ip') return $this->ip;
    }

    public function __isset($name)
    {
        if($name == 'ip') return isset($this->ip);
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

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->day = (new \DateTime("now"))->format('Y-m-d');
    }
}
