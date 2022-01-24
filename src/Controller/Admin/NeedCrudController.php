<?php

namespace App\Controller\Admin;

use App\Entity\Need;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class NeedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Need::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('besoins')
                    ->setEntityLabelInSingular('besoin')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title', 'Titre'),
            TextField::new('user', 'Auteur')->onlyOnIndex(),
            DateTimeField::new('date_add', 'Publié le')->onlyOnIndex(),
            TextareaField::new('description')->hideOnIndex(),
            TextField::new('documentFile', 'Fichier')
                        ->setFormType(VichFileType::class)
                        ->onlyOnForms(),
            IntegerField::new('delay', 'Délai')->onlyOnForms(),
            NumberField::new('budget')->onlyOnForms(),
            ChoiceField::new('nature', 'Statut')->setChoices(fn() => [
                'En attente' => Need::PENDING, 
                'Désactivé' => Need::DISABLED,
                'Validé' => Need::CONFIRMED, 
                'Publié' => Need::PUBLISHED,
                'Rejecté' => Need::REJECTED, 
                'Expiré' => Need::EXPIRED
                ])->hideWhenCreating()
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
