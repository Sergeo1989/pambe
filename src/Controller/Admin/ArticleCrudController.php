<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\CategoryArticle;
use App\Entity\KeywordArticle;
use App\Form\ArticleImageFormType;
use App\Service\ContextService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('articles')
                    ->setEntityLabelInSingular('article')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('title', 'Titre'),
            DateTimeField::new('date_add', 'Date d\'ajout')->onlyOnIndex(),
            DateTimeField::new('date_upd', 'Date de mise à jour')->onlyOnIndex(),
            BooleanField::new('status', 'Status')->hideWhenCreating(),
            TextEditorField::new('content', 'Contenu')->hideOnIndex(),
            CollectionField::new('articleImages', 'Images d\'articles')->setEntryType(ArticleImageFormType::class)
                            ->setHelp("Résolution: 770x490 pixels")->onlyOnForms(),
            AssociationField::new('categoryArticles', 'Catégories', CategoryArticle::class),
            AssociationField::new('keywords', 'Mots Clés', KeywordArticle::class)
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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setAdmin($this->context->getUser()->getAdmin());
        $entityInstance->setSlug($this->context->slug($entityInstance->getTitle()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setSlug($this->context->slug($entityInstance->getTitle()));
        parent::persistEntity($entityManager, $entityInstance);
    }
}
