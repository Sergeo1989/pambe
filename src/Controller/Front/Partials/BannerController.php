<?php

namespace App\Controller\Front\Partials;

use App\Repository\BannerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BannerController extends AbstractController
{
    private $bannerRepo;

    public function __construct(BannerRepository $bannerRepo)
    {
        $this->bannerRepo = $bannerRepo;
    }

    public function index(): Response
    {    
        return $this->render('front/partials/banner.html.twig', [
            'banners' => $this->bannerRepo->findAll()
        ]);
    }
}