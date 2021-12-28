<?php

namespace App\Controller\Front;

use App\Entity\Professional;
use App\Entity\ProfessionalImage;
use App\Entity\ProfessionalLike;
use App\Entity\Qualification;
use App\Entity\Review;
use App\Entity\Service;
use App\Form\Professional\Edit\CoordonneeFormType;
use App\Form\Professional\Edit\GalleryFormType;
use App\Form\Professional\Edit\InformationFormType;
use App\Form\ReviewFormType;
use App\Repository\ProfessionalImageRepository;
use App\Repository\ProfessionalLikeRepository;
use App\Repository\QualificationRepository;
use App\Repository\ServiceRepository;
use App\Service\ContextService;
use App\Service\ProfessionalService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfessionalController extends AbstractController
{
    private $errors = [];
    private $context;
    private $translator;
    private $paginator;
    private $professionalService;
    private $likeRepo;
    private $qualificationRepo;
    private $serviceRepo;
    private $proImgRepo;
    private $request;
    private $response;
    private $status = 200;

    public function __construct(
        ContextService $context, 
        TranslatorInterface $translator,
        PaginatorInterface $paginator, 
        ProfessionalService $professionalService,
        ProfessionalLikeRepository $likeRepo,
        QualificationRepository $qualificationRepo,
        ServiceRepository $serviceRepo,
        ProfessionalImageRepository $proImgRepo)
    {
        $this->context = $context;
        $this->translator = $translator;
        $this->paginator = $paginator;
        $this->professionalService = $professionalService;
        $this->likeRepo = $likeRepo;
        $this->qualificationRepo = $qualificationRepo;
        $this->serviceRepo = $serviceRepo;
        $this->proImgRepo = $proImgRepo;
    }

    public function show(Professional $professional, Request $request): Response
    {
        $professional_id = (int)$request->request->get('professional_id');
        $rating = $request->request->get('rating') ?? 0;
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        if ($request->request->count() > 0) {
            if(empty($name))
                $this->errors['name'] = 'Entrez un nom.';
            if(empty($email))
                $this->errors['email'] = 'Entrez votre adresse email.';
            if(empty($message))
                $this->errors['message'] = 'Entrez votre avis.';
            if(count($this->errors) == 0){
                $review = new Review();
                $review->setName($name);
                $review->setEmail($email);
                $review->setDescription($message);
                $review->setScore((int)$rating);
                $review->setProfessional($this->professionalService->getProfessional($professional_id));

                $this->context->save($review);
                return $this->redirectToRoute("app_professional_show", ["slug" => $professional->getSlug()]);
            }
        }

        // A terminer: Empêcher le professionnel d'augmenter ses propre vues
        $this->professionalService->addView($professional);
        
        return $this->render('front/professional/show.html.twig', [
            'professional' => $professional, 
            'errors' => $this->errors 
        ]);
    }

    public function new(Request $request): Response
    {
        $data = $this->professionalService->getAllNewProfessional();

        $professionals = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/new/index.html.twig', compact('professionals'));
    }
    
    public function vip(Request $request): Response
    {
        $data = $this->professionalService->getAllVipProfessional();

        $professionals = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/vip/index.html.twig', compact('professionals'));
    }

    public function like(Professional $professional): Response
    {
        $user = $this->context->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($professional->isLikedByUser($user)) {
            $like = $this->likeRepo->findOneBy(["professional" => $professional, "user" => $user]);
            $this->context->delete($like);

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $this->likeRepo->count(["professional" => $professional])
            ], 200);
        }

        $like = new ProfessionalLike();
        $like->setProfessional($professional)
             ->setUser($user);

        $this->context->save($like);

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $this->likeRepo->count(["professional" => $professional])
        ], 200);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function information(Request $request)
    { 
        $professional = $this->context->getUser()->getProfessional();

        if(is_null($professional))
        {
            $professional = new Professional();
            $slug = $this->context->slug($this->context->getUser()->getEmail());
            $professional->setSlug($slug);
            $professional->setUser($this->context->getUser());
            $this->context->save($professional);
        }

        $form = $this->createForm(InformationFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');

            if($form->get('saveAndContinue')->isClicked())
                return $this->redirectToRoute('app_professional_coordonnee');
            if($form->get('save')->isClicked())
                return $this->redirectToRoute('app_professional_information');
        }
         
        return $this->render('front/professional/edit/information.html.twig', [
            'informationForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function coordonnee(Request $request)
    {
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(CoordonneeFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->context->save($professional);

            $message = $this->translator->trans('success.message');

            $this->addFlash('success', $message);
            
            if($form->get('saveAndContinue')->isClicked())
                return $this->redirectToRoute('app_professional_service');
            if($form->get('save')->isClicked())
                return $this->redirectToRoute('app_professional_coordonnee');
        }

        return $this->render('front/professional/edit/coordonnee.html.twig', [
            'coordonneeForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function gallery(Request $request)
    {
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(GalleryFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $legend = $form->get("legend")->getData();
            $gallerie = $form->get("gallerie")->getData();
            foreach ($gallerie as $image) {
                $proImage = new ProfessionalImage();
                $proImage->setImageFile($image);
                $proImage->setLegend($legend);
                $professional->addGallery($proImage);
            }

            $this->context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');
            return $this->redirectToRoute('app_professional_gallery');
        }

        return $this->render('front/professional/edit/gallery.html.twig', [
            'galleryForm' => $form->createView(),
        ]);
    }

    public function deleteImage(ProfessionalImage $proImage, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'.$proImage->getId(), $data['_token'])){
            $this->context->delete($proImage);
            return new JsonResponse(["status" => true]);
        }else
            return new JsonResponse(["error" => "Token invalid"], 400);
        
    }

    public function getImage(ProfessionalImage $proImage, Request $request)
    {
        return new JsonResponse(["status" => true, "value" => $proImage]); 
    }

    public function editImage(Request $request)
    {
        if(!$this->context->getUser())
            return new JsonResponse(["status" => false]); 

        $gallery_id = $request->request->get('id');
        $file = $request->files->get('file');
        $legend = $request->get('legend');

        $proImage = $this->proImgRepo->find($gallery_id);

        if(isset($proImage)){
            $proImage->setLegend($legend);
            if($file) $proImage->setImageFile($file);
            
            return new JsonResponse(["status" => true, "value" => $this->context->save($proImage)]); 
        }else
            return new JsonResponse(["status" => false]); 
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function service(Request $request)
    {
        return $this->render('front/professional/edit/service.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function training(Request $request)
    {
        return $this->render('front/professional/edit/training.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function reference(Request $request)
    {
        return $this->render('front/professional/edit/reference.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function option(Request $request)
    {
        return $this->render('front/professional/edit/option.html.twig');
    }

    public function displayAjax(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(
                array(
                    'status' => 400,
                    'message' => 'Bad Error'
                ),
                400
            );
        }

        $this->request = $request;

        $this->response = [];
        
        $action = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $this->request->get("action"))));
        if (!empty($action) && method_exists($this, 'displayAjax' . $action)) {
            $this->{'displayAjax' . $action}();
        }

        return new JsonResponse($this->response, $this->status);
    }

    private function displayAjaxGetService()
    {
        $service_id = (int)$this->request->get('id');

        $service = $this->serviceRepo->find($service_id);

        if(isset($service))
            $this->response = [
                'status' => true,
                'value' => $service
            ];
        else
            $this->response = [
                'status' => false
            ];
    }

    private function displayAjaxAddService()
    {
        $file = $this->request->files->get('file');
        $title = trim($this->request->get('title'));
        $price = trim($this->request->get('price'));
        $unit = trim($this->request->get('unit'));
        $description = $this->request->get('description');
        if(empty($file))
            $this->response = [
                'status' => false,
                'message' => 'Entrer une image.'
            ];
        elseif(empty($title))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un titre.'
            ];
        elseif(!is_numeric($price))
            $this->response = [
                'status' => false,
                'message' => 'Entrer un nombre.'
            ];
        else{
            $service = new Service();
            $service->setTitle($title)
                    ->setPrice($price)
                    ->setUnit($unit)
                    ->setThumbnailFile($file)
                    ->setDescription($description)
                    ->setProfessional($this->context->getUser()->getProfessional());

            $this->response = [
                'status' => true,
                'value' => $this->context->save($service)
            ];
        }
    }

    private function displayAjaxEditService()
    {
        $service_id = (int)$this->request->get('id');
        $file = $this->request->files->get('file');
        $title = trim($this->request->get('title'));
        $price = trim($this->request->get('price'));
        $unit = trim($this->request->get('unit'));
        $description = $this->request->get('description');

        $service = $this->serviceRepo->find($service_id);

        if(isset($service)){
            if(!empty($file)) $service->setThumbnailFile($file);
           
            $service->setTitle($title)
                    ->setPrice($price)
                    ->setUnit($unit)
                    ->setDescription($description);

            $this->response = [
                'status' => true,
                'value' => $this->context->save($service)
            ];
        }
        else
            $this->response = [
                'status' => false
            ];
    }

    private function displayAjaxRemoveService()
    {
        $service_id = (int)$this->request->get('id');

        $service = $this->serviceRepo->find($service_id);

        if(isset($service)){
            $this->context->delete($service);
            $this->response = [
                'status' => true
            ];
        }
        else
            $this->response = [
                'status' => false
            ];
    }

    private function displayAjaxGetExperience()
    {
        $this->getQualification();
    }

    private function displayAjaxAddExperience()
    {
        $this->addQualification(Qualification::EXPERIENCE);
    }

    private function displayAjaxEditExperience()
    {
        $this->editQualification();
    }

    private function displayAjaxRemoveExperience()
    {
        $this->removeQualification();
    }

    private function displayAjaxGetQualification()
    {
        $this->getQualification();
    }

    private function displayAjaxAddQualification()
    {
        $this->addQualification(Qualification::QUALIFICATION);
    }

    private function displayAjaxEditQualification()
    {
        $this->editQualification();
    }

    private function displayAjaxRemoveQualification()
    {
        $this->removeQualification();
    }

    private function displayAjaxGetCertification()
    {
        $this->getQualification();
    }

    private function displayAjaxAddCertification()
    {
        $this->addQualification(Qualification::CERTIFICATE);
    }

    private function displayAjaxEditCertification()
    {
        $this->editQualification();
    }

    private function displayAjaxRemoveCertification()
    {
        $this->removeQualification();
    }

    private function getQualification()
    {
        $qualification_id = (int)$this->request->get('id');

        $qualification = $this->qualificationRepo->find($qualification_id);

        if(isset($qualification))
            $this->response = [
                'status' => true,
                'value' => $qualification
            ];
        else
            $this->response = [
                'status' => false
            ];
    }

    private function addQualification($type)
    {
        $title = trim($this->request->get('title'));
        $place = trim($this->request->get('place'));
        $date_start = $this->request->get('start_date');
        $date_end = $this->request->get('end_date');
        $description = $this->request->get('description');
 
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
            $qualification->setDescription($description);
            $qualification->setType($type);
            $qualification->setProfessional($this->context->getUser()->getProfessional());

            $this->response = [
                'status' => true,
                'value' => $this->context->save($qualification)
            ];
        }
    }

    private function editQualification()
    {
        $qualification_id = (int)$this->request->get('id');
        $title = trim($this->request->get('title'));
        $place = trim($this->request->get('place'));
        $date_start = $this->request->get('start_date');
        $date_end = $this->request->get('end_date');
        $description = $this->request->get('description');

        $qualification = $this->qualificationRepo->find($qualification_id);

        if(isset($qualification)){
            $qualification->setTitle($title);
            $qualification->setPlace($place);
            $qualification->setStartDate(new \DateTime($date_start));
            $qualification->setEndDate(new \DateTime($date_end));
            $qualification->setDescription($description);

            $this->response = [
                'status' => true,
                'value' => $this->context->save($qualification)
            ];
        }else{
            $this->response = [
                'status' => false
            ];
        }
    }

    private function removeQualification()
    {
        $qualification_id = (int)$this->request->get('id');

        $qualification = $this->qualificationRepo->find($qualification_id);

        if(isset($qualification)){
            $this->context->delete($qualification);
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
