<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\CategoryArticleRepository;
use App\Repository\CommentRepository;

class BlogService
{
    private $context;
    private $categoryArtRepo;
    private $articleRepo;
    private $commentRepo;

    public function __construct(
        ContextService $context,
        CategoryArticleRepository $categoryArtRepo, 
        ArticleRepository $articleRepo, 
        CommentRepository $commentRepo)
    {
        $this->context = $context;
        $this->categoryArtRepo = $categoryArtRepo;
        $this->articleRepo = $articleRepo;
        $this->commentRepo = $commentRepo;
    }

    /**
     * Cette fonction retourne toutes les catégories d'articles ayant un statut actif
     *
     * @return CategoryArticle[]
     */
    public function getAllArticleCategory(){
        return $this->categoryArtRepo->findBy(['status' => true]);
    }

    /**
     * Cette fonction retourne tous les articles ayant un statut actif
     *
     * @return Article[]
     */
    public function getAllArticle(){
        $articles = $this->articleRepo->findBy(['status' => true], ['date_add' => 'DESC']);
        
        return $this->context->sort($articles, 'position');
    }
}