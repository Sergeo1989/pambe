<?php

namespace App\Controller\Front;

use App\Repository\TestimonialRepository;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private $testimonialRepo;
    private $blogService;

    public function __construct(TestimonialRepository $testimonialRepo, BlogService $blogService)
    {
        $this->testimonialRepo = $testimonialRepo;
        $this->blogService = $blogService;
    }

    public function index(): Response
    {
        $testimonials = $this->testimonialRepo->findBy(['status' => true]);
        $articles = $this->blogService->getAllArticle();

        return $this->render('front/home/index.html.twig', [
            'testimonials' => $testimonials,
            'articles' => $articles,
        ]);
    }

    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
     
        return $this->redirect($request->headers->get('referer'));
    }
}
