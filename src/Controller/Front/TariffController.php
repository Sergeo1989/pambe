<?php

namespace App\Controller\Front;

use App\Repository\TariffOptionRepository;
use App\Repository\TariffRepository;
use App\Service\ContextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TariffController extends AbstractController
{
    private $context;
    
    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    public function index(TariffRepository $tariffRepo, TariffOptionRepository $tariffOptionRepo): Response
    {
        $data = $tariffRepo->findAll();
        $dataOption = $tariffOptionRepo->findAll();

        $tariffs = $this->context->sort($data, 'position');
        $tariffOptions = $this->context->sort($dataOption, 'position');

        return $this->render('front/tariff/index.html.twig', compact('tariffs', 'tariffOptions'));
    }

}
