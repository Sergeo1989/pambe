<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\KeywordArticleRepository;

class BlogService
{
    private $context;
    private $categoryArtRepo;
    private $keywordArtRepo;
    private $articleRepo;
    private $commentRepo;

    public function __construct(
        ContextService $context,
        CategoryArticleRepository $categoryArtRepo, 
        KeywordArticleRepository $keywordArtRepo, 
        ArticleRepository $articleRepo, 
        CommentRepository $commentRepo)
    {
        $this->context = $context;
        $this->categoryArtRepo = $categoryArtRepo;
        $this->keywordArtRepo = $keywordArtRepo;
        $this->articleRepo = $articleRepo;
        $this->commentRepo = $commentRepo;
    }

    /**
     * Cette fonction retourne toutes les catégories d'articles ayant un statut actif
     *
     * @return CategoryArticle[]
     */
    public function getAllArticleCategory()
    {
        return $this->categoryArtRepo->findBy(['status' => true]);
    }

    /**
     * Cette fonction retourne toutes les mots clés ayant un statut actif
     *
     * @return KeywordArticle[]
     */
    public function getAllArticleKeyword()
    {
        return $this->keywordArtRepo->findBy(['status' => true]);
    }

    /**
     * Cette fonction retourne tous les articles ayant un statut actif
     *
     * @return Article[]
     */
    public function getAllArticle()
    {
        $articles = $this->articleRepo->findBy(['status' => true], ['date_add' => 'DESC']);
        
        return $this->context->sort($articles, 'position');
    }

    /**
     * Cette fonction retourne les 5 derniers articles ayant un statut actif
     *
     * @return Article[]
     */
    public function getLastFiveArticle()
    {
        return $this->articleRepo->findBy(['status' => true], ['date_add' => 'DESC'], 5);
    }

    /**
     * Cette fonction retourne tous les articles par popularité et ayant un statut actif
     *
     * @return Article[]
     */
    public function getAllPopularArticle()
    {
        $articles = $this->articleRepo->findBy(['status' => true], ['date_add' => 'DESC']);

        return $this->context->sort($articles, 'view');
    }

    public function getAllComments(Article $article)
    {
        return $this->commentRepo->findBy(['article' => $article, 'status' => true], ['date_add' => 'DESC']);
    }

    public function search($words = null)
    {
        return $this->articleRepo->search($words);
    }
}