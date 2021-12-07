<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\CategoryArticle;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(): Response
    {
        $articles = $this->blogService->getAllArticle();

        return $this->render('front/blog/index.html.twig', compact('articles'));
    }

    public function show(Article $article): Response
    {
        return $this->render('front/blog/show.html.twig', compact('article'));
    }

    public function category(CategoryArticle $category): Response
    {
        return $this->render('front/blog/category/index.html.twig', compact('category'));
    }
}
