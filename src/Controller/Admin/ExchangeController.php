<?php

namespace App\Controller\Admin;

use App\Entity\Invite;
use App\Repository\ExchangeRepository;
use App\Repository\InviteRepository;
use App\Service\ContextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends AbstractController
{
    private $inviteRepo;
    private $exchangeRepo;

    public function __construct(InviteRepository $inviteRepo, ExchangeRepository $exchangeRepo)
    {
        $this->inviteRepo = $inviteRepo;
        $this->exchangeRepo = $exchangeRepo;
    }

    public function index(): Response
    {
        return $this->render('admin/exchange/index.html.twig', [
            'invites' => $this->inviteRepo->findAll()
        ]);
    }

    public function message(Invite $invite, ContextService $context): Response
    {
        $exchanges = $this->exchangeRepo->getExchangesByInvite($invite);
        foreach ($exchanges as $exchange) {
            $exchange->setIsRead(true);
            $context->save($exchange);
        } 
        
        return $this->render('admin/exchange/message.html.twig', compact('invite'));
    }
}
