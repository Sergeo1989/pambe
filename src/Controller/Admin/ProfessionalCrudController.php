<?php

namespace App\Controller\Admin;

use App\Entity\CategoryProfessional;
use App\Entity\Country;
use App\Entity\Language;
use App\Entity\Professional;
use App\Entity\Qualification;
use App\Entity\Region;
use App\Entity\Skill;
use App\Form\UserFormType;
use App\Form\SocialFormType;
use App\Form\ProfessionalImageFormType;
use App\Form\ServiceFormType;
use App\Repository\ProfessionalRepository;
use App\Repository\QualificationRepository;
use App\Repository\SkillRepository;
use App\Service\ContextService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfessionalCrudController extends AbstractCrudController
{
    private $response;
    private $context;
    private $professionalRepo;
    private $qualificationRepo;
    private $skillRepo;
    private $hasher;
    private $contextService;

    public function __construct(
        UserPasswordHasherInterface $hasher, 
        ProfessionalRepository $professionalRepo, 
        QualificationRepository $qualificationRepo,
        SkillRepository $skillRepo,
        ContextService $contextService)
    {
        $this->hasher = $hasher;
        $this->professionalRepo = $professionalRepo;
        $this->qualificationRepo = $qualificationRepo;
        $this->skillRepo = $skillRepo;
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
        $profile = TextField::new('user.profile', 'Profil')
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
        $social_media = TextField::new('socialUrl', 'Médias Sociaux')->setFormType(SocialFormType::class);;
        $website = UrlField::new('user.website', 'Site web');
        $date_add = DateTimeField::new('date_add', 'Date d\'ajout')->setCssClass('admin-text-align');
        $date_upd = DateTimeField::new('date_upd', 'Date de mise à jour')->setCssClass('admin-text-align');
        $country = AssociationField::new('user.country', 'Pays')
                                ->setFieldFqcn(Country::class)
                                ->setFormTypeOptions(['class' => Country::class]);
        $region = AssociationField::new('user.region', 'Région')
                                ->setFieldFqcn(Region::class)
                                ->setFormTypeOptions(['class' => Region::class]);
        $city = TextField::new('user.city', 'Ville');
        $langues = AssociationField::new('languages', 'Langues', Language::class);
        $categories = AssociationField::new('category_professionals', 'Catégories', CategoryProfessional::class);
        $default_category = AssociationField::new('category_professional_default', 'Catégorie principale', CategoryProfessional::class);
        $short_description = TextEditorField::new('short_description', 'Courte description')
                            ->setFormTypeOptions(['empty_data' => '']);
        $description = TextEditorField::new('description', 'Description')
                            ->setFormTypeOptions(['empty_data' => '']);
        $verified = BooleanField::new('verified', 'Vérifié');
        $status = BooleanField::new('status', 'Status');

        if (Crud::PAGE_INDEX === $pageName)
            return [$id, $email, $name, $date_add, $date_upd, $verified, $status];
        elseif(Crud::PAGE_EDIT === $pageName)
            return [$email, $firstname, $lastname, $phone, $address, $website, $profile, $cover, $galleries, $level, $default_category, $country, $region, $city, $langues, $categories, $short_description, $description, $services, $social_media];
        elseif(Crud::PAGE_DETAIL === $pageName)
            return [$email, $name, $phone, $address, $website, $default_category, $region, $city, $langues, $categories, $short_description, $description, $verified, $status];
        elseif(Crud::PAGE_NEW === $pageName)
            return [$user, $cover, $galleries, $default_category, $langues, $categories, $short_description, $description];
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
                    return $action->setLabel('Ajouter un professionnel');
                });
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $entityInstance->getUser();
        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function edit(AdminContext $context)
    {
        $request = $context->getRequest()->request->all();
        
        if(!empty($request) && ($request["ea"]["newForm"]["btn"] === Action::SAVE_AND_CONTINUE || $request["ea"]["newForm"]["btn"] === Action::SAVE_AND_RETURN)){
            $name = $request["Professional"]["skill"];
            if(empty($name)) return parent::edit($context);
            $id = $request["Professional"]["skill_id"];
            if ($id == "0" || $id == "") {
                $skill = new Skill();
                $skill->setName($name);
                $context->getEntity()->getInstance()->setSkill($skill);
            }else{
                $skill = $this->skillRepo->find((int)$id);
                $context->getEntity()->getInstance()->setSkill($skill);
            }
        }
        return parent::edit($context);
    }

    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        $form = parent::createEditForm($entityDto, $formOptions, $context);
        $skill = $entityDto->getInstance()->getSkill();
        $form->get("skill")->setData($skill ? $skill->getName() : '');
        $form->get("skill_id")->setData($skill ? strval($skill->getId()) : '0');
        return $form;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        $formBuilder->add('skill', TextType::class, [
            'required' => true,
            'label' => 'Compétence',
            'mapped'=> false
            ])
            ->add('skill_id', HiddenType::class, [
            'mapped'=> false,
            'empty_data' => '0'
        ]);
        return $formBuilder;
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

            $this->response = [
                'status' => true,
                'value' => $this->contextService->save($experience)
            ];
        }
    }

    private function displayAjaxRemoveExperience()
    {
        $experience_id = (int)$this->context->getRequest()->request->all()['experience_id'];

        $experience = $this->qualificationRepo->find($experience_id);

        if(isset($experience)){
            $this->contextService->delete($experience);
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

            $this->response = [
                'status' => true,
                'value' => $this->contextService->save($qualification)
            ];
        }
    }

    private function displayAjaxRemoveQualification()
    {
        $qualification_id = (int)$this->context->getRequest()->request->all()['qualification_id'];

        $qualification = $this->qualificationRepo->find($qualification_id);

        if(isset($qualification)){
            $this->contextService->delete($qualification);
            $this->response = [
                'status' => true
            ];
        }
        else
            $this->response = [
                'status' => false
            ];
    }

    private function displayAjaxGetSkills()
    {
        $search = $this->context->getRequest()->request->all()['q'];
        if (null != $search) {
            $skills = $this->skillRepo->findAllByTerm($search);
        } else {
            $skills = $this->skillRepo->findAll();
        }

        $this->response = [
            'status' => 200,
            'data' => $skills
        ];
    }
}
