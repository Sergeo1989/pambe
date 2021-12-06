<?php

namespace App\Service;

use App\Entity\Professional;
use App\Repository\CategoryProfessionalRepository;
use App\Repository\ProfessionalRepository;

class ProfessionalService
{
    private $categoryProRepo;
    private $professionalRepo;

    public function __construct(CategoryProfessionalRepository $categoryProRepo, ProfessionalRepository $professionalRepo)
    {
        $this->categoryProRepo = $categoryProRepo;
        $this->professionalRepo = $professionalRepo;
    }


    /**
     * Cette fonction retourne toutes les catégories de professionnels ayant un statut actif
     *
     * @return CategoryProfessional[]
     */
    public function getAllProfessionalCategory(){
        return $this->categoryProRepo->findBy(['status' => true]);
    }

    /**
     * Cette fonction retourne toutes les catégories de professionnels populaires et ayant un statut actif
     *
     * @return CategoryProfessional[]
     */
    public function getAllPopularProfessionalCategory()
    {
        $categories_pro = $this->getAllProfessionalCategory();

        usort($categories_pro, function ($a, $b)
        {
            if ($a->getView() == $b->getView())
                return 0;
            return ($a->getView() < $b->getView()) ? 1 : -1;
        });

        return $categories_pro;
    }

    /**
     * Cette fonction retourne tous les professionnels vérifiés et ayant un statut actif
     *
     * @return Professional[]
     */
    public function getAllProfessional()
    {
        return $this->professionalRepo->findBy(['verified' => true, 'status' => true]);
    }

    /**
     * Cette fonction retourne tous les professionnels vip vérifiés, ayant un statut actif et triés 
     * selon une position
     *
     * @return Professional[]
     */
    public function getAllVipProfessional()
    {
        $professionals_vip = array_filter($this->getAllProfessional(), function($professional){
            if($professional->getLevel() == Professional::VIP)
                return $professional;
        });

        usort($professionals_vip, function ($a, $b)
        {
            if ($a->getPosition() == $b->getPosition())
                return 0;
            return ($a->getPosition() < $b->getPosition()) ? 1 : -1;
        });

        return $professionals_vip;
    }

    /**
     * Cette fonction retourne tous les nouveaux professionnels vérifiés, ayant un statut actif et triés 
     * selon une position
     *
     * @return Professional[]
     */
    public function getAllNewProfessional()
    {
        $professionals_new = array_filter($this->getAllProfessional(), function($professional){
            $date_add = $professional->getDateAdd();
            $date_now = new \DateTime("now");
            if($date_now->diff($date_add)->format("%a") <= 20)
                return $professional;
        });

        usort($professionals_new, function ($a, $b)
        {
            if ($a->getPosition() == $b->getPosition())
                return 0;
            return ($a->getPosition() < $b->getPosition()) ? 1 : -1;
        });

        return $professionals_new;
    }
}