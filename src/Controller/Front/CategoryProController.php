<?php

namespace App\Controller\Front;

use App\Entity\CategoryProfessional;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryProController extends AbstractController
{
    public function show(CategoryProfessional $categoryPro): Response
    {
        return $this->render('front/professional/category/show.html.twig', compact('categoryPro'));
    }

    public function showPopular(): Response
    {
        return $this->render('front/professional/category/show_popular.html.twig');
    }
}
