<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BannerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Banner::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Bannière')
                    ->setEntityLabelInPlural('Bannières')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Toutes les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('imageFile', 'Bannière')
                        ->setFormType(VichImageType::class)
                        ->setFormTypeOptions(['constraints' => [
                            new Image([
                                'minWidth' => 1920,
                                'minHeight' => 1024,
                                'minWidthMessage' => 'La largeur de l\'image est trop petite',
                                'minHeightMessage' => 'La hauteur de l\'image est trop petite'
                                ])
                        ]])
                        ->setHelp('Résolution: 1200x300 pixels')
                        ->onlyOnForms(),
            ImageField::new('image', 'Image')
                        ->setBasePath('/uploads/images/banner/')->setCssClass('admin-image-size')
                        ->onlyOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->remove(Crud::PAGE_INDEX, Action::DETAIL)
                ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                    return $action->setLabel('Modifier')->setIcon('fa fa-edit')->addCssClass('btn btn-warning');
                })
                ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                    return $action->setLabel('Supprimer')->setIcon('fa fa-trash')->addCssClass('btn btn-outline-danger');
                })
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setLabel('Ajouter une bannière');
                });
    }
}
