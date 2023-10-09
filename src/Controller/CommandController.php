<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandController extends AbstractController
{
    private $application;

    public function __construct(KernelInterface $kernel) {
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }

    public function cacheClear(): Response
    {
        $input = new ArrayInput([
            'command' => 'cache:clear'
        ]);

        $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, true);

        $this->application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }

    public function extractTranslation($lang): Response
    {
        $input = new ArrayInput([
            'command' => 'translation:extract',
            '--force' => $lang
        ]);

        $output = new BufferedOutput();
        $this->application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }
}