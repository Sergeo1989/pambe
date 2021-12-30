<?php

namespace App\EventListener;

use App\Entity\CategoryProfessional;
use App\Service\ContextService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof CategoryProfessional) {
            return;
        }

        $entity->setSlug($this->context->slug($entity->getName()));
    }
}