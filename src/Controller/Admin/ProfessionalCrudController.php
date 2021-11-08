<?php

namespace App\Controller\Admin;

use App\Entity\CategoryProfessional;
use App\Entity\Professional;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfessionalCrudController extends AbstractCrudController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getEntityFqcn(): string
    {
        return Professional::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('professionnels')
                    ->setEntityLabelInSingular('professionnel')
                    ->setPageTitle(Crud::PAGE_INDEX, 'Tous les %entity_label_plural%')
                    ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
                    ->setPageTitle(Crud::PAGE_DETAIL, '%entity_label_singular%');
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
        $website = UrlField::new('website', 'Site web');
        $date_add = DateTimeField::new('date_add', 'Date d\'ajout');
        $date_upd = DateTimeField::new('date_upd', 'Date de mise à jour');
        $country = AssociationField::new('country', 'Pays', Country::class);
        $region = AssociationField::new('region', 'Région', Region::class);
        $city = AssociationField::new('city', 'Ville', City::class);
        $default_category = AssociationField::new('category_professional_default', 'Catégorie principale', CategoryProfessional::class);
        $short_description = TextEditorField::new('short_description', 'Courte description');
        $description = TextEditorField::new('description', 'Description');
        $verified = BooleanField::new('verified', 'Vérifié');
        $status = BooleanField::new('status', 'Status');

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $email, $name, $date_add, $date_upd, $default_category, $verified, $status];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$email, $firstname, $lastname, $phone, $website, $default_category, $country, $region, $city, $short_description, $description];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$email, $name, $phone, $website, $default_category, $country, $region, $city, $short_description, $description, $verified, $status];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$user, $website, $default_category, $country, $region, $city, $short_description, $description];
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

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDateUpd(new \DateTime('now'));
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setDateAdd(new \DateTime('now'));
        $entityInstance->setDateUpd(new \DateTime('now'));
        $entityInstance->setStatus(false);
        $entityInstance->setVerified(false);
        parent::persistEntity($entityManager, $entityInstance);
    }
}
