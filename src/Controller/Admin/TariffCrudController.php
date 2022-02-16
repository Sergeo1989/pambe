<?php

namespace App\Controller\Admin;

use App\Entity\Tariff;
use App\Form\TariffOptionFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TariffCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tariff::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('tarifs')
                    ->setEntityLabelInSingular('tarif')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $position = IntegerField::new('position');
        $title = TextField::new('title', 'Titre');
        $color = TextField::new('color', 'Couleur');
        $most_popular = BooleanField::new('most_popular', 'Le plus populaire ?');
        $amount = MoneyField::new('amount', 'Prix')->setCurrency('XAF')->setCustomOption('storedAsCents', false);
        $position = IntegerField::new('position');
        $options = CollectionField::new('tariffTariffOptions', 'Options')->setEntryType(TariffOptionFormType::class);

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $title, $color, $amount, $most_popular, $position];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$title, $color, $amount, $most_popular, $position, $options];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$title, $color, $amount, $most_popular, $position];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$title, $color, $amount, $most_popular, $options];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                    return $action->setLabel('Visualiser')->setIcon('fa fa-eye')->addCssClass('btn btn-info');
                })
                ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                    return $action->setLabel('Modifier')->setIcon('fa fa-edit')->addCssClass('btn btn-warning');
                })
                ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                    return $action->setLabel('Supprimer')->setIcon('fa fa-trash')->addCssClass('btn btn-outline-danger');
                })
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setLabel('Ajouter un tarif');
                });
    }
}
 
