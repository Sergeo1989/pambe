<?php

namespace App\Controller\Front;

use App\Entity\Professional;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalController extends AbstractController
{
    public function show(Professional $professional): Response
    {
        return $this->render('front/professional/show.html.twig', compact('professional'));
    }

    public function new(): Response
    {
        return $this->render('front/professional/new/index.html.twig');
    }
    
    public function vip(): Response
    {
        return $this->render('front/professional/vip/index.html.twig');
    }
}
