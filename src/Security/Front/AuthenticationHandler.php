<?php

namespace App\Security\Front;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
	private $session;

	/**
	 * Constructor
	 *
	 * @param 	RouterInterface $router
	 * @param 	Session $session
	 */
	public function __construct(RouterInterface $router, Session $session)
	{
		$this->router  = $router;
		$this->session = $session;
	}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if($request->isXmlHttpRequest())
            return new JsonResponse(['success' => true]);

        if($this->session->get('_security.main.target_path'))
            return new RedirectResponse($this->session->get('_security.main.target_path'));
        
        return new RedirectResponse($this->router->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if($request->isXmlHttpRequest())
            return new JsonResponse(['success' => false, 'message' => $exception->getMessage()]);

        if($request->hasSession())
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}
