<?php

namespace App\Controller\Front;

use App\Repository\BannerRepository;
use App\Repository\SocialUrlRepository;
use App\Repository\TestimonialRepository;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $testimonialRepo;
    private $blogService;

    public function __construct(TestimonialRepository $testimonialRepo, BlogService $blogService)
    {
        $this->testimonialRepo = $testimonialRepo;
        $this->blogService = $blogService;
    }

    public function index(): Response
    {
        $testimonials = $this->testimonialRepo->findBy(['status' => true]);
        $articles = $this->blogService->getAllArticle();

        return $this->render('front/home/index.html.twig', [
            'testimonials' => $testimonials,
            'articles' => $articles,
        ]);
    }

    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);
     
        return $this->redirect($request->headers->get('referer'));
    }

    public function socialUrl(SocialUrlRepository $socialUrlRepo): Response
    {
        $social_url = $socialUrlRepo->find(1);
        if(isset($social_url))        
            return $this->render('front/partials/social_url.html.twig', compact('social_url'));
        else
            throw new BadRequestException('Vous devez charger la fixture "SocialFixture au préalable" !');
    }

    public function banner(BannerRepository $bannerRepo): Response
    {    
        return $this->render('front/partials/banner.html.twig', [
            'banners' => $bannerRepo->findAll()
        ]);
    }

    /**
     * @Route("/ping", name="ping", methods={"POST"})
     */
    public function ping(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/my-private-topic',
            json_encode(['status' => 'success'])
        );

        $hub->publish($update);

        return $this->redirectToRoute('app_home');
    }
}
