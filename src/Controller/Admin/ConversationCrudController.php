<?php

namespace App\Controller\Admin;

use App\Entity\Conversation;
use App\Entity\Message;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ConversationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conversation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->overrideTemplates([
                        'crud/detail' => 'admin/crud/conversation/detail.html.twig',
                    ])
                    ->setEntityLabelInPlural('conversations')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_DETAIL, 'Tous les messages');
                   
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $messages = AssociationField::new('messages', 'Messages', Message::class);
        $date_add = DateTimeField::new('date_add', 'Date de création');

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $date_add, $messages];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$messages, $date_add];

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                    return $action->setIcon('fa fa-eye')->addCssClass('btn btn-info');
                })
                ->remove(Crud::PAGE_INDEX, Action::NEW)
                ->remove(Crud::PAGE_INDEX, Action::EDIT)
                ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }
}
