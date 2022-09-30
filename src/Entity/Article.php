<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tchoulom\ViewCounterBundle\Model\ViewCountable;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"article:read"}, "swagger_definition_name"="Read"},
 *      denormalizationContext={"groups"={"article:write"}, "swagger_definition_name"="Write"},
 *      collectionOperations={
 *          "get"={},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *      },
 *      itemOperations={
 *          "get"={},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"}
 *      }
 * ) 
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "content": "partial"})
 * @ApiFilter(BooleanFilter::class, properties={"status"})
 * @ApiFilter(OrderFilter::class, properties={"date_add"})
 * @ApiFilter(DateFilter::class, properties={"date_add"})
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(name="article", indexes={@ORM\Index(columns={"title", "content"}, flags={"fulltext"})})
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Ce titre d'article existe déjà."
 * )
 */
class Article implements ViewCountable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"article:read", "catarticle:read", "keyarticle:read"})
     */
    private $id;
 
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article:read", "catarticle:read", "keyarticle:read"})
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article:read", "catarticle:read", "keyarticle:read"})
     */
    private $date_add;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article:read", "catarticle:read", "keyarticle:read"})
     */
    private $date_upd;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="articles")
     */
    private $admin;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryArticle::class, inversedBy="articles")
     * @Groups({"article:write", "keyarticle:read"})
     */
    private $categoryArticles;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     * @Groups({"article:read"})
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=ArticleImage::class, mappedBy="article", cascade={"persist", "remove"})
     */
    private $articleImages;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    protected $views = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"article:read", "article:write", "catarticle:read", "keyarticle:read"})
     */
    private $share;

    /**
     * @ORM\ManyToMany(targetEntity=KeywordArticle::class, inversedBy="articles")
     * @Groups({"article:write", "catarticle:read"})
     */
    private $keywords;

    /**
     * @ORM\OneToMany(targetEntity=ArticleView::class, mappedBy="article")
     */
    private $viewCounters;

    public function __construct()
    {
        $this->categoryArticles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->articleImages = new ArrayCollection();
        $this->keywords = new ArrayCollection();
        $this->viewCounters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    /**
     * @return Collection|CategoryArticle[]
     */
    public function getCategoryArticles(): Collection
    {
        return $this->categoryArticles;
    }

    public function addCategoryArticle(CategoryArticle $categoryArticle): self
    {
        if (!$this->categoryArticles->contains($categoryArticle)) {
            $this->categoryArticles[] = $categoryArticle;
            $categoryArticle->addArticle($this);
        }

        return $this;
    }

    public function removeCategoryArticle(CategoryArticle $categoryArticle): self
    {
        if ($this->categoryArticles->removeElement($categoryArticle)) {
            $categoryArticle->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|ArticleImage[]
     */
    public function getArticleImages(): Collection
    {
        return $this->articleImages;
    }

    public function addArticleImage(ArticleImage $articleImage): self
    {
        if (!$this->articleImages->contains($articleImage)) {
            $this->articleImages[] = $articleImage;
            $articleImage->setArticle($this);
        }

        return $this;
    }

    public function removeArticleImage(ArticleImage $articleImage): self
    {
        if ($this->articleImages->removeElement($articleImage)) {
            // set the owning side to null (unless already changed)
            if ($articleImage->getArticle() === $this) {
                $articleImage->setArticle(null);
            }
        }

        return $this;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    public function getShare(): ?int
    {
        return $this->share;
    }

    public function setShare(?int $share): self
    {
        $this->share = $share;

        return $this;
    }

    /**
     * @return Collection|KeywordArticle[]
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(KeywordArticle $keyword): self
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords[] = $keyword;
            $keyword->addArticle($this);
        }

        return $this;
    }

    public function removeKeyword(KeywordArticle $keyword): self
    {
        if ($this->keywords->removeElement($keyword)) {
            $keyword->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection|ArticleView[]
     */
    public function getViewCounters(): Collection
    {
        return $this->viewCounters;
    }

    public function addViewCounter(ArticleView $viewCounter): self
    {
        if (!$this->viewCounters->contains($viewCounter)) {
            $this->viewCounters[] = $viewCounter;
            $viewCounter->setArticle($this);
        }

        return $this;
    }

    public function removeViewCounter(ArticleView $viewCounter): self
    {
        if ($this->viewCounters->removeElement($viewCounter)) {
            // set the owning side to null (unless already changed)
            if ($viewCounter->getArticle() === $this) {
                $viewCounter->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->status = true;
        $this->position = 0;
        $this->share = 0;
        $this->date_add = new \DateTime("now");
        $this->date_upd = new \DateTime("now");
    }
 
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_upd = new \DateTime("now");
    }
}
