<?php

namespace App\Controller\Front;

use App\Entity\Professional;
use App\Service\ProfessionalService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalController extends AbstractController
{
    private $paginator;
    private $professionalService;

    public function __construct(PaginatorInterface $paginator, ProfessionalService $professionalService)
    {
        $this->paginator = $paginator;
        $this->professionalService = $professionalService;
    }

    public function show(Professional $professional): Response
    {
        return $this->render('front/professional/show.html.twig', compact('professional'));
    }

    public function new(Request $request): Response
    {
        $data = $this->professionalService->getAllNewProfessional();

        $professionals = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/new/index.html.twig', compact('professionals'));
    }
    
    public function vip(Request $request): Response
    {
        $data = $this->professionalService->getAllVipProfessional();

        $professionals = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/vip/index.html.twig', compact('professionals'));
    }
}
