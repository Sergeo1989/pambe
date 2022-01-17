<?php

namespace App\Controller\Front;

use App\Entity\Need;
use App\Entity\Proposal;
use App\Form\NeedFormType;
use App\Form\ProposalFormType;
use App\Repository\NeedRepository;
use App\Service\ContextService;
use App\Service\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class NeedController extends AbstractController
{
    private $context;
    private $paginator;
    private $translator;
    private $needRepo;
    private $emailSender;

    public function __construct(ContextService $context, PaginatorInterface $paginator, TranslatorInterface $translator, NeedRepository $needRepo, $emailSender)
    {
        $this->context = $context;
        $this->paginator = $paginator;
        $this->translator = $translator;
        $this->needRepo = $needRepo;
        $this->emailSender = $emailSender;
    }

    public function index(Request $request): Response
    {
        $data = $this->needRepo->findBy(['status' => true], ['date_add' => 'DESC']);

        $needs = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/professional/need/index.html.twig', compact('needs'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function create(Request $request, ContextService $context): Response
    {
        $need = new Need();
        $needForm = $this->createForm(NeedFormType::class, $need);
        $needForm->handleRequest($request);

        if($needForm->isSubmitted() && $needForm->isValid()) {
            $need->setUser($context->getUser());
            $context->save($need);
            $message = $this->translator->trans('global.your_need_has_been_successfully_registered.');
            $this->addFlash("message", $message);
            return $this->redirectToRoute('app_professional_need_create');
        }

        return $this->render('front/professional/need/create.html.twig', ['needForm' => $needForm->createView()]);
    }

    public function show(Need $need, Request $request, ContextService $context): Response
    {
        $proposal = new Proposal();
        $proposalForm = $this->createForm(ProposalFormType::class, $proposal);
        $proposalForm->handleRequest($request);

        if($proposalForm->isSubmitted() && $proposalForm->isValid()) {
            $proposal->setProfessional($context->getUser()->getProfessional());
            $proposal->setNeed($need);
            $context->save($proposal);

            $message = $this->translator->trans('global.your_proposal_is_awaiting_moderation.');
            $this->addFlash("message", $message);
            return $this->redirectToRoute('app_professional_need_show', ['id' => $need->getId()]);
        }

        return $this->render('front/professional/need/show.html.twig', ['need' => $need, 'proposalForm' => $proposalForm->createView()]);
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
    public function nature(Proposal $proposal, Need $need, $nature, MailerService $mailer): Response
    {
        if($nature === 'accepted'){
            $proposal->setNature(Proposal::ACCEPTED);
            foreach ($need->getProposals()->getValues() as $value) 
                if($value !== $proposal){
                    $value->setNature(Proposal::REFUSED);
                    $this->context->save($value);
                }
            $subject = $this->translator->trans('global.validation_of_proposal');
            $mailer->send(
                $subject, 
                $this->emailSender, 
                $proposal->getProfessional()->getUser()->getEmail(), 
                'front/email/need.html.twig', 
                ['need' => $need]
            );
        }elseif ($nature === 'refused') {
            $proposal->setNature(Proposal::REFUSED);
            $proposal = $this->context->save($proposal);
        }
        
        return $this->redirectToRoute('app_professional_proposal', ['id' => $need->getId()]);
    }
}
