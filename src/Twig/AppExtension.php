<?php

namespace App\Twig;

use App\Service\ProfessionalService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $professionalService;

    public function __construct(ProfessionalService $professionalService)
    {
        $this->professionalService = $professionalService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('vippro', [$this, 'professionalsVip']),
            new TwigFunction('newpro', [$this, 'professionalsNew']),
            new TwigFunction('catpoppro', [$this, 'categoriesProPopular'])
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
}