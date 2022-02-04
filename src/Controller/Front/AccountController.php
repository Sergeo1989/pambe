<?php

namespace App\Controller\Front;

use App\Entity\Conversation;
use App\Entity\Need;
use App\Entity\ProfessionalImage;
use App\Entity\Proposal;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\NeedFormType;
use App\Form\Professional\Edit\GalleryFormType;
use App\Form\Professional\Edit\InformationFormType as EditInformationFormType;
use App\Form\Professional\Edit\SocialMediaFormType;
use App\Form\ProposalFormType;
use App\Form\User\CoordonneeFormType;
use App\Form\User\InformationFormType;
use App\Repository\AdminRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Service\ContextService;
use App\Service\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $translator;
    private $paginator;
    private $emailSender;

    public function __construct(TranslatorInterface $translator, PaginatorInterface $paginator, $emailSender)
    {
        $this->translator = $translator;
        $this->paginator = $paginator;
        $this->emailSender = $emailSender;
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request, ContextService $context): Response
    {
        $user = $context->getUser();
        $userForm = $this->createForm(InformationFormType::class, $user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){
            $context->save($user);
            $this->addFlash('message', $this->translator->trans('global.your_information_has_been_correctly_modified!'));
            return $this->redirectToRoute('app_account');
        }

        return $this->render('front/account/index.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function password(Request $request, UserPasswordHasherInterface $encoder, ContextService $context): Response
    {
        $user = $context->getUser();
        $passwordForm = $this->createForm(ChangePasswordFormType::class);
        $passwordForm->handleRequest($request);

        if($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $oldPassword = $passwordForm->get("oldPassword")->getData();
            $newPassword = $passwordForm->get("newPassword")->getData();

            if($encoder->isPasswordValid($user, $oldPassword)){
                $user->setPassword($encoder->hashPassword($user, $newPassword));
                $context->save($user);
                $this->addFlash('success', $this->translator->trans('global.your_password_has_been_successfully_changed !'));
            }else{
                $this->addFlash('error', $this->translator->trans('global.enter_a_good_password !'));
            }
            
            return $this->redirectToRoute('app_account_password');
        }

        return $this->render('front/account/password.html.twig', [
            'passwordForm' => $passwordForm->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function need(ContextService $context, Request $request)
    {
        $data = array_reverse($context->getUser()->getNeeds()->getValues());
        $needs = $this->paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('front/account/need.html.twig', compact('needs'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function showneed(Need $need)
    {
        return $this->render('front/account/showneed.html.twig', compact('need'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function proposalneed(Need $need)
    {
        $proposals = $need->getProposals();
      
        return $this->render('front/account/proposalneed.html.twig', compact('proposals'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function createneed(ContextService $context, Request $request)
    {
        $need = new Need();
        $needForm = $this->createForm(NeedFormType::class, $need);
        $needForm->handleRequest($request);

        if($needForm->isSubmitted() && $needForm->isValid()) {
            $need->setUser($context->getUser());
            $context->save($need);
            $message = $this->translator->trans('global.your_need_has_been_successfully_registered.');
            $this->addFlash("message", $message);
            return $this->redirectToRoute('app_account_need');
        }

        return $this->render('front/account/createneed.html.twig', ['needForm' => $needForm->createView()]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function editneed(Need $need, ContextService $context, Request $request)
    {
        $needForm = $this->createForm(NeedFormType::class, $need);
        $needForm->handleRequest($request);

        if($needForm->isSubmitted() && $needForm->isValid()) {
            $need->setNature(Need::PENDING);
            $context->save($need);
            $message = $this->translator->trans('global.your_need_has_been_successfully_registered.');
            $this->addFlash("message", $message);
            return $this->redirectToRoute('app_account_need');
        }

        return $this->render('front/account/editneed.html.twig', ['need' => $need, 'needForm' => $needForm->createView()]);
    }

    public function deleteneed(Need $need, ContextService $context)
    {
        $context->delete($need);
        $this->addFlash('message', $this->translator->trans('global.need_successfully_removed!'));
        return $this->redirectToRoute('app_account_need');
    }

    public function republishneed(Need $need, ContextService $context, MailerService $mailer, AdminRepository $adminRepo)
    {
        $need->setNature(Need::PENDING);
        $need = $context->save($need);
        foreach ($adminRepo->findBy(['status' => true]) as $admin) 
            if($admin->getManageNeeds())
                $mailer->send(
                    'Ré-publication d\'un besoin',
                    $this->emailSender,
                    $admin->getUser()->getEmail(),
                    'front/email/republishneed.html.twig',
                    ['need' => $need]
                );
            
        $this->addFlash('message', $this->translator->trans('global.your_need_is_awaiting_moderation.'));
        return $this->redirectToRoute('app_account_need');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function coordonnee(ContextService $context, Request $request)
    {
        $user = $context->getUser();
        $userForm = $this->createForm(CoordonneeFormType::class, $user);
        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()) {
            $context->save($user);
            $this->addFlash('message', $this->translator->trans('global.your_information_has_been_correctly_modified!'));
            return $this->redirectToRoute('app_account_coordonnee');
        }

        return $this->render('front/account/coordonnee.html.twig', ['userForm' => $userForm->createView()]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function information(Request $request, ContextService $context)
    { 
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);
        $professional = $context->getUser()->getProfessional();

        $form = $this->createForm(EditInformationFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $context->save($professional);

            $message = $this->translator->trans('global.content_successfully_registered!');
            $this->addFlash('success', $message);

            if($form->get('saveAndContinue')->isClicked())
                return $this->redirectToRoute('app_account_professional_social');
            if($form->get('save')->isClicked())
                return $this->redirectToRoute('app_account_professional_information');
        }
         
        return $this->render('front/account/information.html.twig', [
            'informationForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function social(Request $request, ContextService $context)
    {
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);

        $professional = $context->getUser()->getProfessional();
        $form = $this->createForm(SocialMediaFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $context->save($professional);

            $message = $this->translator->trans('global.content_successfully_registered!');
            $this->addFlash('success', $message);
            
            if($form->get('saveAndContinue')->isClicked())
                return $this->redirectToRoute('app_account_professional_service');
            if($form->get('save')->isClicked())
                return $this->redirectToRoute('app_account_professional_social');
        }

        return $this->render('front/account/socialmedia.html.twig', [
            'socialForm' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function service(ContextService $context)
    {
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);

        return $this->render('front/account/service.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function training(ContextService $context)
    {
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);

        return $this->render('front/account/training.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function reference(ContextService $context)
    {
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);

        return $this->render('front/account/reference.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function gallery(Request $request, ContextService $context)
    {
        $message = $this->translator->trans('global.you_are_not_a_professional.');
        $this->denyAccessUnlessGranted('edit', $context->getUser(), $message);

        $professional = $context->getUser()->getProfessional();
        $form = $this->createForm(GalleryFormType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $legend = $form->get("legend")->getData();
            $gallery = $form->get("gallery")->getData();

            foreach ($gallery as $image) {
                $proImage = new ProfessionalImage();
                $proImage->setImageFile($image);
                $proImage->setLegend($legend);
                $professional->addGallery($proImage);
            }

            $context->save($professional);

            $this->addFlash('success', 'Contenu enregistré avec succès !');
            return $this->redirectToRoute('app_account_professional_gallery');
        }

        return $this->render('front/account/gallery.html.twig', [
            'galleryForm' => $form->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function proposal(ContextService $context)
    {
        if(!$context->getUser()->getProfessional())
            return $this->redirectToRoute('app_professional_create');

        $proposals = array_reverse($context->getUser()->getProfessional()->getProposals()->getValues());
        return $this->render('front/account/proposal.html.twig', compact('proposals'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function showproposal(Proposal $proposal)
    {
        return $this->render('front/account/showproposal.html.twig', compact('proposal'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function editproposal(Proposal $proposal, Request $request, ContextService $context)
    {
        $proposalForm = $this->createForm(ProposalFormType::class, $proposal);
        $proposalForm->handleRequest($request);

        if($proposalForm->isSubmitted() && $proposalForm->isValid()) {
            $context->save($proposal);

            $message = $this->translator->trans('global.your_proposal_is_awaiting_moderation.');
            $this->addFlash("message", $message);
            return $this->redirectToRoute('app_account_professional_proposal_edit', ['id' => $proposal->getId()]);
        }
        return $this->render('front/account/editproposal.html.twig', [
            'proposal' => $proposal, 
            'proposalForm' => $proposalForm->createView()
        ]);
    }

    public function deleteproposal(Proposal $proposal, ContextService $context)
    {
        $context->delete($proposal);
        $this->addFlash('message', $this->translator->trans('global.need_successfully_removed!'));
        return $this->redirectToRoute('app_account_professional_proposal');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function option(ContextService $context)
    {
        return $this->render('front/account/option.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function profile(User $user)
    {
        return $this->render('front/account/profile.html.twig', compact('user'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function conversation(ConversationRepository $conversationRepo, ContextService $context)
    {
        $conversations = $conversationRepo->getConversations($context->getUser());
        return $this->render('front/account/conversation.html.twig', compact('conversations'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function message(Conversation $conversation, ContextService $context, MessageRepository $messageRepo)
    {
        $messages = $messageRepo->findBy(['recipient' => $context->getUser(), 'conversation' => $conversation]);
        foreach ($messages as $message) {
            $message->setIsRead(true);
            $context->save($message);
        }
        return $this->render('front/account/message.html.twig', compact('conversation'));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function notification()
    {
        return $this->render('front/account/notification.html.twig');
    }
}
