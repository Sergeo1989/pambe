<?php

namespace App\Controller\Front\Partials;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class MainMenuController extends AbstractController
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function index(): Response
    {
        try {
            $main_menu = Yaml::parseFile($this->kernel->getProjectDir().'/config/packages/main_menu.yaml');
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
        return $this->render('front/partials/main_menu.html.twig', compact('main_menu'));
    }
}
