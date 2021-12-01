<?php

namespace App\Controller\Front\Partials;

use App\Repository\SocialUrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class SocialUrlController extends AbstractController
{
    private $socialUrlRepo;

    public function __construct(SocialUrlRepository $socialUrlRepo)
    {
        $this->socialUrlRepo = $socialUrlRepo;
    }

    public function index(): Response
    {
        $social_url = $this->socialUrlRepo->find(1);
        if(isset($social_url))        
            return $this->render('front/partials/social_url.html.twig', compact('social_url'));
        else
            throw new BadRequestException('Vous devez charger la fixture "SocialFixture au préalable" !');
    }
}