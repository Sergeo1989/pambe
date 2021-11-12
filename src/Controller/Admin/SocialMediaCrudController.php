<?php

namespace App\Controller\Admin;

use App\Entity\SocialMedia;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SocialMediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialMedia::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('média social')
                    ->setEntityLabelInPlural('médias sociaux')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Toutes les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('iconFile', 'Icône')
                        ->setFormType(VichImageType::class)
                        ->onlyOnForms(),
            ImageField::new('icon', 'Icône')
                        ->setBasePath('/uploads/images/socialmedia/')
                        ->onlyOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                    return $action->setIcon('fa fa-eye')->addCssClass('btn btn-info');
                })
                ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                    return $action->setIcon('fa fa-edit')->addCssClass('btn btn-warning');
                })
                ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                    return $action->setIcon('fa fa-trash')->addCssClass('btn btn-outline-danger');
                })
                ;
    }
}
