<?php

namespace App\Controller\Admin;

use App\Entity\CategoryProfessional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategoryProfessionalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategoryProfessional::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('catégories de professionnels')
                    ->setEntityLabelInSingular('catégorie de professionnel')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom'),
            TextField::new('job', 'Métier'),
            TextEditorField::new('description')->hideOnIndex(),
            TextField::new('iconFile', 'Icône')
                        ->setFormType(VichImageType::class)
                        ->setHelp("La largeur et la hauteur doivent être comprise entre 200px et 400px")
                        ->onlyOnForms(),
            ImageField::new('icon', 'Image')
                        ->setBasePath('/uploads/images/categorypro/')->setCssClass('admin-image-size')
                        ->onlyOnIndex(),
            BooleanField::new('status', 'Statut')->onlyOnIndex(),
            ChoiceField::new('grade', 'Niveau')->setChoices(fn() => ['Standard' => CategoryProfessional::NORMAL, 'Populaire' => CategoryProfessional::POPULAR])->onlyWhenUpdating(),
            IntegerField::new('position')->onlyWhenUpdating()
        ];
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
                    return $action->setLabel('Ajouter une catégorie');
                });
    }
}
