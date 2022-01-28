<?php

namespace App\Controller\Front;

use App\Entity\Need;
use App\Entity\Proposal;
use App\Form\NeedFormType;
use App\Form\ProposalFormType;
use App\Repository\AdminRepository;
use App\Repository\NeedRepository;
use App\Service\ContextService;
use App\Service\MailerService;
use App\Service\ProfessionalService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class NeedController extends AbstractController
{
    private $professionalService;
    private $context;
    private $paginator;
    private $translator;
    private $needRepo;
    private $emailSender;

    public function __construct(ProfessionalService $professionalService, ContextService $context, PaginatorInterface $paginator, TranslatorInterface $translator, NeedRepository $needRepo, $emailSender)
    {
        $this->professionalService = $professionalService;
        $this->context = $context;
        $this->paginator = $paginator;
        $this->translator = $translator;
        $this->needRepo = $needRepo;
        $this->emailSender = $emailSender;
    }

    public function index(Request $request): Response
    {
        $data = $this->professionalService->getAllNeed();

        $needs = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/need/index.html.twig', compact('needs'));
    }
 
    public function create(Request $request, ContextService $context): Response
    {
        $need = new Need();
        $needForm = $this->createForm(NeedFormType::class, $need);
        $needForm->handleRequest($request);

        if($needForm->isSubmitted() && $needForm->isValid()) {
            $need->setUser($context->getUser());
            $need = $context->save($need);
            $url = $this->generateUrl('app_account_need_show', ['id' => $need->getId()]);
            $message = $this->translator->trans('global.your_need_has_been_successfully_registered.');

            $content['msg'] = $message;
            $content['url'] = $url; 
            $this->addFlash("message", $content);
            return $this->redirectToRoute('app_professional_need_create');
        }

        $needForm = $needForm->createView();
        return $this->render('front/professional/need/create.html.twig', compact('needForm'));
    }

    public function show(Need $need): Response
    {
        return $this->render('front/professional/need/show.html.twig', compact('need'));
    }

    public function newproposal(Need $need, Request $request, ContextService $context): Response
    {
        $proposal = new Proposal();
        $proposalForm = $this->createForm(ProposalFormType::class, $proposal);
        $proposalForm->handleRequest($request);

        if($proposalForm->isSubmitted() && $proposalForm->isValid()) {
            $proposal->setProfessional($context->getUser()->getProfessional());
            $proposal->setNeed($need);
            $proposal = $context->save($proposal);

            $message = $this->translator->trans('global.your_proposal_is_awaiting_moderation.');
            $url = $this->generateUrl('app_account_professional_proposal_show', ['id' => $proposal->getId()]);
            $content['msg'] = $message;
            $content['url'] = $url; 
            $this->addFlash("message", $content);
            return $this->redirectToRoute('app_professional_proposal_create', ['id' => $need->getId()]);
        }

        $proposalForm = $proposalForm->createView();
        return $this->render('front/professional/proposal/create.html.twig', compact('proposalForm', 'need'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function proposal(Need $need): Response
    {
        return $this->render('front/professional/need/proposals.html.twig', compact('need'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function nature(Proposal $proposal, $nature, MailerService $mailer, AdminRepository $adminRepo): Response
    {
        $need = $proposal->getNeed();
        if($nature === 'accepted'){
            foreach ($need->getProposals()->getValues() as $value){ 
                if($value !== $proposal)
                    $value->setNature(Proposal::REFUSED);  
                else
                    $value->setNature(Proposal::ACCEPTED);
                $this->context->save($value);
            }
            $subject = $this->translator->trans('global.validation_of_proposal');
            $mailer->send(
                $subject, 
                $this->emailSender, 
                $proposal->getProfessional()->getUser()->getEmail(), 
                'front/email/need.html.twig', 
                ['proposal' => $proposal]
            );
            foreach ($adminRepo->findBy(['status' => true]) as $admin) 
                if($admin->getManageProposals())
                    $mailer->send(
                        'Validation d\'une proposition',
                        $this->emailSender,
                        $admin->getUser()->getEmail(),
                        'front/email/adminneed.html.twig',
                        ['proposal' => $proposal]
                    );
        }elseif ($nature === 'refused') {
            $proposal->setNature(Proposal::REFUSED);
            $this->context->save($proposal);
        }

        return $this->redirectToRoute('app_account_professional_need_proposal', ['id' => $need->getId()]);
    }
}
