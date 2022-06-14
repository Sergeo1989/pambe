<?php

namespace App\Entity;

use App\Repository\ArticleViewRepository;
use Doctrine\ORM\Mapping as ORM;
use Tchoulom\ViewCounterBundle\Entity\ViewCounter;
use Tchoulom\ViewCounterBundle\Entity\ViewCounterInterface;
use Tchoulom\ViewCounterBundle\Model\ViewCountable;

/**
 * @ORM\Entity(repositoryClass=ArticleViewRepository::class)
 */
class ArticleView extends ViewCounter
{
    /**
     * @ORM\ManyToOne(targetEntity=Article::class, cascade={"persist"}, inversedBy="viewCounters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return ViewCountable
     */
    public function getPage(): ViewCountable
    {
        return $this->article;
    }

    /**
     * @param ViewCountable $professional
     * @return ViewCounterInterface
     */
    public function setPage(ViewCountable $article): ViewCounterInterface
    {
        $this->article = $article;
    
        return $this;
    }
}
