<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\CategoryArticle;
use App\Service\BlogService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $paginator;
    private $blogService;

    public function __construct(PaginatorInterface $paginator, BlogService $blogService)
    {
        $this->paginator = $paginator;
        $this->blogService = $blogService;
    }

    public function index(Request $request): Response
    {
        $data = $this->blogService->getAllArticle();

        $articles = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('front/blog/index.html.twig', compact('articles'));
    }

    public function show(Article $article): Response
    {
        return $this->render('front/blog/show.html.twig', compact('article'));
    }

    public function category(CategoryArticle $category, Request $request): Response
    {
        $data = $category->getArticles();
        $articles = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('front/blog/category/index.html.twig', compact('category', 'articles'));
    }
}
