<?php

namespace App\Controller\Front;

use App\Entity\CategoryProfessional;
use App\Service\ProfessionalService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 
class CategoryProController extends AbstractController
{
    private $paginator;
    private $professionalService;

    public function __construct(PaginatorInterface $paginator, ProfessionalService $professionalService)
    {
        $this->paginator = $paginator;
        $this->professionalService = $professionalService;
    }

    public function show(CategoryProfessional $category, Request $request): Response
    { 
        $data = $category->getAllProfessionals()->getValues();

        $value = $request->query->get('sort');

        $data = $this->professionalService->simpleSorting($value, $data);

        $professionals = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/index.html.twig', compact('category', 'professionals', 'data'));
    }

    public function popular(Request $request): Response
    {
        $data = $this->professionalService->getAllPopularProfessionalCategory();

        $categories = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            16
        );

        return $this->render('front/professional/category/popular/index.html.twig', compact('categories'));
    }
}
