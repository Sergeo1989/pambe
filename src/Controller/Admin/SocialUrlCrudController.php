<?php

namespace App\Controller\Admin;

use App\Entity\SocialUrl;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SocialUrlCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialUrl::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('médias sociaux')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier l\'url de vos %entity_label_plural%');
                   
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            UrlField::new('facebook')->onlyWhenUpdating(),
            UrlField::new('twitter')->onlyWhenUpdating(),
            UrlField::new('youtube')->onlyWhenUpdating(),
            UrlField::new('instagram')->onlyWhenUpdating(),
            UrlField::new('linkedin')->onlyWhenUpdating(),
            UrlField::new('whatsapp')->onlyWhenUpdating(),
            UrlField::new('pinterest')->onlyWhenUpdating()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->remove(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN);
    }
}
