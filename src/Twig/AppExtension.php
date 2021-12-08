<?php

namespace App\Twig;

use App\Service\BlogService;
use App\Service\ProfessionalService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $professionalService;
    private $blogService;

    public function __construct(ProfessionalService $professionalService, BlogService $blogService)
    {
        $this->professionalService = $professionalService;
        $this->blogService = $blogService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('vippro', [$this, 'professionalsVip']),
            new TwigFunction('newpro', [$this, 'professionalsNew']),
            new TwigFunction('catpoppro', [$this, 'categoriesProPopular']),
            new TwigFunction('catart', [$this, 'categoriesArt']),
            new TwigFunction('keyart', [$this, 'keywordsArt'])
        ];
    }

    public function professionalsVip(){
        return $this->professionalService->getAllVipProfessional();
    }

    public function professionalsNew(){
        return $this->professionalService->getAllNewProfessional();
    }

    public function categoriesProPopular(){
        return $this->professionalService->getAllPopularProfessionalCategory();
    }

    public function categoriesArt(){
        return $this->blogService->getAllArticleCategory();
    }

    public function keywordsArt(){
        return $this->blogService->getAllArticleKeyword();
    }
}