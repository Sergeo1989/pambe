<?php

namespace App\Twig;

use App\Entity\Professional;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use App\Service\BlogService;
use App\Service\ContextService;
use App\Service\ProfessionalService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $context;
    private $userRepo;
    private $professionalService;
    private $blogService;
    private $menuRepo;

    public function __construct(ContextService $context, UserRepository $userRepo, ProfessionalService $professionalService, BlogService $blogService, MenuRepository $menuRepo)
    {
        $this->context = $context;
        $this->userRepo = $userRepo;
        $this->professionalService = $professionalService;
        $this->blogService = $blogService;
        $this->menuRepo = $menuRepo;
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
            new TwigFunction('menus', [$this, 'getMenus']),
            new TwigFunction('users', [$this, 'users']),
            new TwigFunction('pros', [$this, 'professionals']),
            new TwigFunction('vippros', [$this, 'professionalsVip']),
            new TwigFunction('newpros', [$this, 'professionalsNew']),
            new TwigFunction('catspoppro', [$this, 'categoriesProPopular']),
            new TwigFunction('catspro', [$this, 'categoriesPro']),
            new TwigFunction('catsart', [$this, 'categoriesArt']),
            new TwigFunction('keysart', [$this, 'keywordsArt']),
            new TwigFunction('scoreavg', [$this, 'getScoreAverage']),
            new TwigFunction('popart', [$this, 'getPopularArticle']),
            new TwigFunction('lastfiveart', [$this, 'getLast5Article'])
        ];
    }

    public function users()
    {
        return $this->userRepo->findBy(['status' => true]);
    }

    public function professionals()
    {
        return $this->professionalService->getAllProfessional();
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

    public function categoriesPro()
    {
        return $this->professionalService->getAllProfessionalCategory();
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

    public function getMenus()
    {
        $menus = $this->menuRepo->findAll();
        return $this->context->sort($menus, 'position');
    }
}