<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('utilisateurs')
                    ->setEntityLabelInSingular('utilisateur')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $query = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $query->leftJoin('entity.professional', 'p')->leftJoin('entity.admin', 'a')->where('p IS NULL AND a IS NULL');

        return $query;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $email = EmailField::new('email', 'E-mail');
        $lastname = TextField::new('lastname', 'Nom de famille');
        $firstname = TextField::new('firstname', 'Prénom');
        $phone = TelephoneField::new('phone', 'Téléphone');
        $address = TextField::new('address', 'Adresse');
        $website = TextField::new('website', 'Site web');
        $password = TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class);
        $date_add = DateTimeField::new('date_add', 'Date de création');
        $date_upd = DateTimeField::new('date_upd', 'Date de mise à jour');
        $status = BooleanField::new('status', 'Status');

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $email, $lastname, $firstname, $date_add, $date_upd, $status];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$email, $firstname, $lastname, $phone, $address, $website];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$email, $lastname, $firstname, $phone, $address, $status];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$email, $lastname, $firstname, $address, $phone, $password];
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
                    return $action->setLabel('Ajouter un utilisateur');
                });
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setRoles(['ROLE_USER']);
        $entityInstance->setPassword($this->hasher->hashPassword($entityInstance, $entityInstance->getPassword()));
        parent::persistEntity($entityManager, $entityInstance);
    }
}
