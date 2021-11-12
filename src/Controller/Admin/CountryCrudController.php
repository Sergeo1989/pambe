<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CountryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Country::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('pays')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('iso_code', 'Code ISO'),
            TextField::new('name', 'Nom')
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
