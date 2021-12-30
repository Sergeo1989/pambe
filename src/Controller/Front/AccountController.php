<?php

namespace App\Controller\Front;

use App\Form\ChangePasswordFormType;
use App\Form\User\UserFormType;
use App\Service\ContextService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder, ContextService $context): Response
    {
        $passwordForm = $this->createForm(ChangePasswordFormType::class);
        $passwordForm->handleRequest($request);

        $user = $context->getUser();
        $userForm = $this->createForm(UserFormType::class, $user);
        $userForm->handleRequest($request);

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
            
            return $this->redirectToRoute('app_account');
        }elseif($userForm->isSubmitted() && $userForm->isValid()){
            $context->save($user);

            $this->addFlash('message', $this->translator->trans('global.your_information_has_been_correctly_modified!'));
            return $this->redirectToRoute('app_account');
        }

        return $this->render('front/account/index.html.twig', [
            'passwordForm' => $passwordForm->createView(),
            'userForm' => $userForm->createView()
        ]);
    }
}
