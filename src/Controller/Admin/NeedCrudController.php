<?php

namespace App\Controller\Admin;

use App\Entity\Need;
use App\Entity\Notification;
use App\Service\ContextService;
use Doctrine\ORM\EntityManagerInterface;
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
    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

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
            TextField::new('title', 'Titre')->hideWhenUpdating(),
            TextField::new('user', 'Auteur')->onlyOnIndex(),
            DateTimeField::new('date_add', 'Publié le')->onlyOnIndex(),
            TextareaField::new('description')->onlyOnDetail(),
            TextField::new('documentFile', 'Fichier')
                        ->setFormType(VichFileType::class)
                        ->onlyWhenCreating(),
            IntegerField::new('delay', 'Délai (jours)')->hideWhenUpdating(),
            NumberField::new('budget', 'Budget (FCFA)')->hideWhenUpdating(),
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

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $notification = new Notification();
        $notification->setObject($entityInstance);
        $notification->setTitle('Statut du besoin');
        $notification->setUser($entityInstance->getUser());

        if($entityInstance->getNature() == Need::EXPIRED)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' a expiré.');
        elseif($entityInstance->getNature() == Need::CONFIRMED)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' a été validé.');
        elseif($entityInstance->getNature() == Need::DISABLED)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' a été désactivé.');
        elseif($entityInstance->getNature() == Need::PENDING)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' est mis en attente.');
        elseif($entityInstance->getNature() == Need::PUBLISHED)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' a été publié.');
        elseif($entityInstance->getNature() == Need::REJECTED)
            $notification->setContent('Besoin #' . str_pad(strval($entityInstance->getId()), 5, '0', STR_PAD_LEFT) . ' a été rejecté.');

        $this->context->save($notification);
        parent::updateEntity($entityManager, $entityInstance);
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
                    return $action->setLabel('Ajouter un besoin');
                });
    }
}
