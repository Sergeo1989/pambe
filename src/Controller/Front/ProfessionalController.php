<?php

namespace App\Controller\Front;

use App\Entity\Professional;
use App\Entity\ProfessionalImage;
use App\Entity\ProfessionalLike;
use App\Entity\Qualification;
use App\Form\Professional\Edit\CoordonneeFormType;
use App\Form\Professional\Edit\GalleryFormType;
use App\Form\Professional\Edit\InformationFormType;
use App\Form\Professional\Edit\ServiceFormType;
use App\Repository\ProfessionalLikeRepository;
use App\Repository\QualificationRepository;
use App\Service\ContextService;
use App\Service\ProfessionalService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfessionalController extends AbstractController
{
    private $context;
    private $paginator;
    private $professionalService;
    private $likeRepo;
    private $qualificationRepo;
    private $request;
    private $response;
    private $status = 200;

    public function __construct(
        ContextService $context, 
        PaginatorInterface $paginator, 
        ProfessionalService $professionalService,
        ProfessionalLikeRepository $likeRepo,
        QualificationRepository $qualificationRepo)
    {
        $this->context = $context;
        $this->paginator = $paginator;
        $this->professionalService = $professionalService;
        $this->likeRepo = $likeRepo;
        $this->qualificationRepo = $qualificationRepo;
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function index(){

    }

    public function show(Professional $professional): Response
    {
        $url = $this->generateUrl("app_professional_show", ["slug" => $professional->getSlug()]);
        // A terminer: Empêcher le professionnel d'augmenter ses propre vues
        $this->professionalService->addView($professional);
        return $this->render('front/professional/show.html.twig', compact('professional', 'url'));
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
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function information(Request $request)
    { 
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(InformationFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');
            return $this->redirectToRoute('app_professional_coordonnee');
        }
         
        return $this->render('front/professional/edit/information.html.twig', [
            'informationForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function coordonnee(Request $request)
    {
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(CoordonneeFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');
            return $this->redirectToRoute('app_professional_service');
        }

        return $this->render('front/professional/edit/coordonnee.html.twig', [
            'coordonneeForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function gallery(Request $request)
    {
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(GalleryFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
 
            $gallerie = $form->get("gallerie")->getData();
            foreach ($gallerie as $image) {
                $proImage = new ProfessionalImage();
                $proImage->setImageFile($image);
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

    public function deleteImage(ProfessionalImage $proImage, Request $request){
        $data = json_decode($request->getContent(), true);
        
        if($this->isCsrfTokenValid('delete'.$proImage->getId(), $data['_token'])){
            $this->context->delete($proImage);
            return new JsonResponse(["success" => 1]);
        }else{
            return new JsonResponse(["error" => "Token invalid"], 400);
        }
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function service(Request $request)
    {
        $professional = $this->context->getUser()->getProfessional();
        $form = $this->createForm(ServiceFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');
            return $this->redirectToRoute('app_professional_service');
        }

        return $this->render('front/professional/edit/service.html.twig', [
            'serviceForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function training(Request $request)
    {
        return $this->render('front/professional/edit/training.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_PROFESSIONAL')")
     */
    public function reference(Request $request)
    {
        return $this->render('front/professional/edit/reference.html.twig');
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

    private function displayAjaxAddExperience()
    {
        $this->addQualification(Qualification::EXPERIENCE);
    }

    private function displayAjaxRemoveExperience()
    {
        $this->removeQualification();
    }

    private function displayAjaxAddQualification()
    {
        $this->addQualification(Qualification::QUALIFICATION);
    }

    private function displayAjaxRemoveQualification()
    {
        $this->removeQualification();
    }

    private function displayAjaxAddCertification()
    {
        $this->addQualification(Qualification::CERTIFICATE);
    }

    private function displayAjaxRemoveCertification()
    {
        $this->removeQualification();
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
