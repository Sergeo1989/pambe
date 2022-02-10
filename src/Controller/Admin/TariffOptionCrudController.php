<?php

namespace App\Controller\Admin;

use App\Entity\TariffOption;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TariffOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TariffOption::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('options de tarif')
                    ->setEntityLabelInSingular('option de tarif')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $position = IntegerField::new('position');
        $title = TextField::new('title', 'Titre');
        $description = TextEditorField::new('description');
    

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $title, $description, $position];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$title, $description, $position];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$title, $description, $position];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$title, $description];
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
