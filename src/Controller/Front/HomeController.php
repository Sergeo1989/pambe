<?php

namespace App\Controller\Front;
 
use App\Repository\CategoryProfessionalRepository;
use App\Repository\TestimonialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private $categoryProRepo;
    private $testimonialRepo;

    public function __construct(CategoryProfessionalRepository $categoryProRepo, TestimonialRepository $testimonialRepo)
    {
        $this->categoryProRepo = $categoryProRepo;
        $this->testimonialRepo = $testimonialRepo;
    }

    public function index(): Response
    {
        $testimonials = $this->testimonialRepo->findBy(['status' => true]);
        $categories_pro = $this->categoryProRepo->findBy(['status' => true]);


        usort($categories_pro, function ($a, $b)
        {
            if ($a->getView() == $b->getView())
                return 0;
            return ($a->getView() < $b->getView()) ? 1 : -1;
        });

        return $this->render('front/home/index.html.twig', [
            'categories_pro_popular' => $categories_pro,
            'testimonials' => $testimonials
        ]);
    }
}
