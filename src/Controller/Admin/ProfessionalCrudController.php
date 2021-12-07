<?php

namespace App\Controller\Admin;

use App\Entity\CategoryProfessional;
use App\Entity\Language;
use App\Entity\Professional;
use App\Entity\Qualification;
use App\Entity\SocialMedia;
use App\Form\UserFormType;
use App\Form\ProfessionalImageFormType;
use App\Form\ServiceFormType;
use App\Repository\ProfessionalRepository;
use App\Repository\QualificationRepository;
use App\Service\ContextService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfessionalCrudController extends AbstractCrudController
{
    private $response;
    private $context;
    private $em;
    private $professionalRepo;
    private $qualificationRepo;
    private $hasher;
    private $contextService;

    public function __construct(
        UserPasswordHasherInterface $hasher, 
        EntityManagerInterface $em, 
        ProfessionalRepository $professionalRepo, 
        QualificationRepository $qualificationRepo,
        ContextService $contextService)
    {
        $this->hasher = $hasher;
        $this->em = $em;
        $this->professionalRepo = $professionalRepo;
        $this->qualificationRepo = $qualificationRepo;
        $this->contextService = $contextService;
    }

    public static function getEntityFqcn(): string
    {
        return Professional::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->overrideTemplates([
                        'crud/edit' => 'admin/crud/professional/edit.html.twig',
                    ])
                    ->setEntityLabelInPlural('professionnels')
                    ->setEntityLabelInSingular('professionnel')
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
        $profile = TextField::new('profil', 'Profil')
                            ->setFormType(ProfessionalImageFormType::class)
                            ->setHelp("Résolution: 1200x1200 pixels");
        $level = ChoiceField::new('level', 'Niveau')->setChoices(fn() => ['VIP' => Professional::VIP, 'NORMAL' => Professional::NORMAL]);
        $cover = TextField::new('cover', 'Couverture de page')->setFormType(ProfessionalImageFormType::class)
                            ->setHelp("Résolution: 1200x300 pixels");
        $galleries = CollectionField::new('galleries')->setEntryType(ProfessionalImageFormType::class)
                            ->setHelp("Résolution: 1200x1200 pixels");
        $services = CollectionField::new('services')->setEntryType(ServiceFormType::class);
        $name = TextField::new('user', 'Nom complet', User::class);
        $firstname = TextField::new('user.firstname', 'Prénom');
        $phone = TelephoneField::new('user.phone', 'Téléphone');
        $address = TextField::new('user.address', 'Adresse');
        $website = UrlField::new('website', 'Site web');
        $date_add = DateTimeField::new('date_add', 'Date d\'ajout');
        $date_upd = DateTimeField::new('date_upd', 'Date de mise à jour');
        $country = AssociationField::new('country', 'Pays', Country::class);
        $region = AssociationField::new('region', 'Région', Region::class);
        $city = AssociationField::new('city', 'Ville', City::class);
        $langues = AssociationField::new('languages', 'Langues', Language::class);
        $social_medias = AssociationField::new('social_medias', 'Médias sociaux', SocialMedia::class);
        $categories = AssociationField::new('category_professionals', 'Catégories', CategoryProfessional::class);
        $default_category = AssociationField::new('category_professional_default', 'Catégorie principale', CategoryProfessional::class);
        $short_description = TextEditorField::new('short_description', 'Courte description');
        $description = TextEditorField::new('description', 'Description');
        $verified = BooleanField::new('verified', 'Vérifié');
        $status = BooleanField::new('status', 'Status');

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $email, $name, $date_add, $date_upd, $default_category, $verified, $status];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$email, $firstname, $lastname, $phone, $address, $website, $profile, $cover, $galleries, $level, $default_category, $country, $region, $city, $langues, $social_medias, $categories, $short_description, $description, $services];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$email, $name, $phone, $address, $website, $default_category, $country, $region, $city, $langues, $social_medias, $categories, $short_description, $description, $verified, $status];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$user, $website, $profile, $cover, $galleries, $default_category, $country, $region, $city, $langues, $social_medias, $categories, $short_description, $description];
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
        $user = $entityInstance->getUser();
        $user->setRoles(['ROLE_PROFESSIONAL']);
        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
        $entityInstance->setSlug($this->contextService->slug($user));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $entityInstance->getUser();
        $user->setRoles(['ROLE_PROFESSIONAL']);
        $entityInstance->setSlug($this->contextService->slug($user));
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        if (Crud::PAGE_EDIT === $responseParameters->get('pageName')) {
            $qualifications = $responseParameters->get('entity')->getInstance()->getQualifications();
            
            $responseParameters->set('qualifications', $qualifications);
        }
        return $responseParameters;
    }

    public function displayAjax(AdminContext $context)
    {
        if (!$context->getRequest()->isXmlHttpRequest()) {
            return new JsonResponse(
                array(
                    'status' => 400,
                    'message' => 'Bad Error'
                ),
                400
            );
        }

        $this->response = [];
        $this->context = $context;

        $action = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $context->getRequest()->request->all()['action'])));
        if (!empty($action) && method_exists($this, 'displayAjax' . $action)) {
            $this->{'displayAjax' . $action}();
        }

        return new JsonResponse($this->response);
    }

    private function displayAjaxAddExperience()
    {
        $professional_id = (int)$this->context->getRequest()->request->all()['professional_id'];
        $title = trim($this->context->getRequest()->request->all()['title']);
        $place = trim($this->context->getRequest()->request->all()['place']);
        $date_start = $this->context->getRequest()->request->all()['date_start'];
        $date_end = $this->context->getRequest()->request->all()['date_end'];
        $description = $this->context->getRequest()->request->all()['description'];

        if(empty($title))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un titre.'
            ];
        elseif(empty($place))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un lieu.'
            ];
        elseif(strtotime($date_start) > strtotime($date_end))
            $this->response = [
                'status' => false,
                'message' => 'La date de début doit être inférieur à la date de fin.'
            ];
        else{
            $experience = new Qualification();
            $experience->setTitle($title);
            $experience->setPlace($place);
            $experience->setStartDate(new \DateTime($date_start));
            $experience->setEndDate(new \DateTime($date_end));
            $experience->setDescription($description ?? '');
            $experience->setType(Qualification::EXPERIENCE);
            $experience->setProfessional($this->professionalRepo->find($professional_id));
            
            $this->em->persist($experience);
            $this->em->flush();

            $this->response = [
                'status' => true,
                'value' => $experience
            ];
        }
    }

    private function displayAjaxRemoveExperience()
    {
        $experience_id = (int)$this->context->getRequest()->request->all()['experience_id'];

        $experience = $this->qualificationRepo->find($experience_id);

        if(isset($experience)){
            $this->em->remove($experience);
            $this->em->flush();
            $this->response = [
                'status' => true
            ];
        }
        else
            $this->response = [
                'status' => false
            ];
    }

    private function displayAjaxAddQualification(){
        $professional_id = (int)$this->context->getRequest()->request->all()['professional_id'];
        $title = trim($this->context->getRequest()->request->all()['title']);
        $place = trim($this->context->getRequest()->request->all()['place']);
        $date_start = $this->context->getRequest()->request->all()['date_start'];
        $date_end = $this->context->getRequest()->request->all()['date_end'];
        $description = $this->context->getRequest()->request->all()['description'];

        if(empty($title))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un titre.'
            ];
        elseif(empty($place))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un lieu.'
            ];
        elseif(strtotime($date_start) > strtotime($date_end))
            $this->response = [
                'status' => false,
                'message' => 'La date de début doit être inférieur à la date de fin.'
            ];
        else{
            $qualification = new Qualification();
            $qualification->setTitle($title);
            $qualification->setPlace($place);
            $qualification->setStartDate(new \DateTime($date_start));
            $qualification->setEndDate(new \DateTime($date_end));
            $qualification->setDescription($description ?? '');
            $qualification->setType(Qualification::QUALIFICATION);
            $qualification->setProfessional($this->professionalRepo->find($professional_id));

            $this->em->persist($qualification);
            $this->em->flush();

            $this->response = [
                'status' => true,
                'value' => $qualification
            ];
        }
    }

    private function displayAjaxRemoveQualification()
    {
        $qualification_id = (int)$this->context->getRequest()->request->all()['qualification_id'];

        $qualification = $this->qualificationRepo->find($qualification_id);

        if(isset($qualification)){
            $this->em->remove($qualification);
            $this->em->flush();
            $this->response = [
                'status' => true
            ];
        }
        else
            $this->response = [
                'status' => false
            ];
    }
}
