<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCrudController extends AbstractCrudController
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('administrateurs')
                    ->setEntityLabelInSingular('administrateur')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $email = EmailField::new('user.email', 'E-mail');
        $lastname = TextField::new('user.lastname', 'Nom');
        $user = TextField::new('user', false)->setFormType(UserFormType::class);
        $name = TextField::new('user', 'Nom complet', User::class);
        $firstname = TextField::new('user.firstname', 'Prénom');
        $phone = TelephoneField::new('user.phone', 'Téléphone');
        $address = TextField::new('user.address', 'Adresse');
        $date_add = DateTimeField::new('date_add', 'Date de création');
        $date_upd = DateTimeField::new('date_upd', 'Date de mise à jour');
        $status = BooleanField::new('status', 'Status');
        $manage_needs = BooleanField::new('manage_needs', 'Gère les besoins ?');
        $manage_proposals = BooleanField::new('manage_proposals', 'Gère les propositions ?');
        $role = ChoiceField::new('user.roles', 'Rôles')->setChoices(fn() => [
            'Super administrateur' => "ROLE_SUPER_ADMIN", 
            'Admistrateur' => "ROLE_ADMIN", 
            'Modérateur' => "ROLE_MODERATOR"
        ])->allowMultipleChoices()->renderExpanded();

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $email, $name, $date_add, $date_upd, $status];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$email, $firstname, $lastname, $phone, $address, $manage_needs, $manage_proposals, $role];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$email, $name, $phone, $address, $status];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$user, $manage_needs, $manage_proposals];
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
                    return $action->setLabel('Ajouter un administrateur');
                });
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setIdentifier(uniqid(date('Y')));
        $user = $entityInstance->getUser();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
        parent::persistEntity($entityManager, $entityInstance);
    }
}
