<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function __construct()
    {

    }

    public function index(): Response
    {
        return $this->render('front/home/index.html.twig', [
            'users' => []
        ]);
    }
}
