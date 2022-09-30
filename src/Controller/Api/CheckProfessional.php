<?php

namespace App\Controller\Api;

use App\Service\ProfessionalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CheckProfessional extends AbstractController
{
    private $professionalService;

    public function __construct(ProfessionalService $professionalService)
    {
        $this->professionalService = $professionalService;
    }

    public function __invoke(Request $request) : array 
    {
        $word = $request->query->get('word');
        $category_id = $request->query->get('category');
        $address = $request->query->get('address');
        
        $category = $this->professionalService->getCategoryPro((int)$category_id);

        $professionals = array();
        
        if (!empty($this->professionalService
                       ->searchByName($word, $category, $address)))
            $professionals = $this->professionalService
                         ->searchByName($word, $category, $address);
        elseif (!empty($this->professionalService
                           ->searchByService($word, $category, $address)))
            $professionals = $this->professionalService
                         ->searchByService($word, $category, $address);
        elseif (!empty($this->professionalService
                           ->searchByDescription($word, $category, $address)))
            $professionals = $this->professionalService
                         ->searchByDescription($word, $category, $address);

        return $professionals;     
    }
}