<?php

namespace App\Service;

use App\Entity\CategoryProfessional;
use App\Entity\Need;
use App\Entity\Professional;
use App\Repository\CategoryProfessionalRepository;
use App\Repository\NeedRepository;
use App\Repository\ProfessionalRepository;

class ProfessionalService
{
    private $context;
    private $categoryProRepo;
    private $professionalRepo;
    private $needRepo;

    public function __construct(
        ContextService $context, 
        CategoryProfessionalRepository $categoryProRepo,
        ProfessionalRepository $professionalRepo,
        NeedRepository $needRepo)
    {
        $this->context = $context;
        $this->categoryProRepo = $categoryProRepo;
        $this->professionalRepo = $professionalRepo;
        $this->needRepo = $needRepo;
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

        return $this->context->sort($categories_pro, 'view');
    }

    /**
     * Cette fonction retourne tous les professionnels ayant un statut actif
     *
     * @return Professional[]
     */
    public function getAllProfessional()
    {
        return $this->professionalRepo->findBy(['status' => true]);
    }

    /**
     * Cette fonction retourne tous les professionnels vip ayant un statut actif et triés 
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

        return $this->context->sort($professionals_vip, 'position');
    }

    /**
     * Cette fonction retourne tous les nouveaux professionnels ayant un statut actif et triés 
     * selon une position
     *
     * @return Professional[]
     */
    public function getAllNewProfessional()
    {
        $professionals_new = array_filter($this->getAllProfessional(), function($professional){
            $date_add = $professional->getDateAdd();
            $date_now = new \DateTime("now");
            if($date_now->diff($date_add)->format("%a") <= 30)
                return $professional;
        });

        return $this->context->sort($professionals_new, 'position');
    }

    public function getAllNeed()
    {
        return $this->needRepo->findBy(['status' => true, 'nature' => Need::PUBLISHED], ['date_add' => 'DESC']);
    }

    public function getProfessional(int $id): Professional
    {
        return $this->professionalRepo->find($id);
    }

    public function getCategoryPro(int $id)
    {
        return $this->categoryProRepo->find($id) ?? null;
    }

    public function searchByName($words = null, $category = null, $address = null)
    {
        return $this->professionalRepo->searchByName($words, $category, $address);
    }

    public function searchByService($words = null, $category = null, $address = null)
    {
        return $this->professionalRepo->searchByService($words, $category, $address);
    }

    public function searchByQualification($words = null, $category = null, $address = null)
    {
        return $this->professionalRepo->searchByQualification($words, $category, $address);
    }

    public function searchByDescription($words = null, $category = null, $address = null)
    {
        return $this->professionalRepo->searchByDescription($words, $category, $address);
    }

    public function simpleSorting($sort, $data)
    {
        if($sort === 'certified'){
            $data = array_filter($data, function($professional){
                if($professional->getVerified() == true)
                    return $professional;
            });
        }elseif($sort === 'available'){
            $data = array_filter($data, function($professional){
                if($professional->getAvailable() == true)
                    return $professional;
            });
        }
        return $data;
    }
}