<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalController extends AbstractController
{
    public function show(): Response
    {
        return $this->render('front/professional/show.html.twig');
    }
}
