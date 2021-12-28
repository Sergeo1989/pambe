<?php

namespace App\Twig;

use App\Entity\Professional;
use App\Service\BlogService;
use App\Service\ProfessionalService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
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

    public function getFilters(): array
    {
        return [
            new TwigFilter('force_to_int', fn ($value) => intval($value))
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('vippros', [$this, 'professionalsVip']),
            new TwigFunction('newpros', [$this, 'professionalsNew']),
            new TwigFunction('catspoppro', [$this, 'categoriesProPopular']),
            new TwigFunction('catsart', [$this, 'categoriesArt']),
            new TwigFunction('keysart', [$this, 'keywordsArt']),
            new TwigFunction('scoreavg', [$this, 'getScoreAverage']),
            new TwigFunction('popart', [$this, 'getPopularArticle']),
            new TwigFunction('lastfiveart', [$this, 'getLast5Article'])
        ];
    }

    public function professionalsVip()
    {
        return $this->professionalService->getAllVipProfessional();
    }

    public function professionalsNew()
    {
        return $this->professionalService->getAllNewProfessional();
    }

    public function categoriesProPopular()
    {
        return $this->professionalService->getAllPopularProfessionalCategory();
    }

    public function categoriesArt()
    {
        return $this->blogService->getAllArticleCategory();
    }

    public function keywordsArt()
    {
        return $this->blogService->getAllArticleKeyword();
    }

    public function getScoreAverage(Professional $professional)
    {
        $reviews = $professional->getReviews();
        $sum = 0;
        foreach ($reviews as $review) {
            $sum += $review->getScore();
        }
        if(count($reviews) > 0)
            return number_format($sum / count($reviews), 1, '.', ',');
        else 
            return '0.0';
    }

    public function getPopularArticle()
    {
        return $this->blogService->getAllPopularArticle();
    }

    public function getLast5Article()
    {
        return $this->blogService->getLastFiveArticle();
    }
}