<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends AbstractController
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(): Response
    {
        return $this->render('admin/exchange/index.html.twig', [
            'data' => $this->userRepo->getAdminConversation()
        ]);
    }

    public function message(User $user): Response
    {
        return $this->render('admin/exchange/message.html.twig', compact('user'));
    }
}
