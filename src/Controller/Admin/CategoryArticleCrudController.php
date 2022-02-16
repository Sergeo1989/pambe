<?php

namespace App\Controller\Admin;

use App\Entity\CategoryArticle;
use App\Service\ContextService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CategoryArticleCrudController extends AbstractCrudController
{
    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    public static function getEntityFqcn(): string
    {
        return CategoryArticle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('catégories d\'articles')
                    ->setEntityLabelInSingular('catégorie d\'article')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom'),
            TextEditorField::new('description')->hideOnIndex(),
            TextField::new('iconFile', 'Icône')
                        ->setFormType(VichImageType::class)
                        ->setHelp("La largeur et la hauteur doivent être comprise entre 1920px et 601px")
                        ->onlyOnForms(),
            ImageField::new('icon', 'Icône')
                        ->setBasePath('/uploads/images/categoryart/')->setCssClass('admin-image-size')
                        ->onlyOnIndex(),
            BooleanField::new('status')->onlyOnIndex(),
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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($this->context->slug($entityInstance->getName()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($this->context->slug($entityInstance->getName()));
        parent::updateEntity($entityManager, $entityInstance);
    }
}
