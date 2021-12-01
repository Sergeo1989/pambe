<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AccountController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): Response
    {
        return $this->render('front/account/index.html.twig');
    }
}
