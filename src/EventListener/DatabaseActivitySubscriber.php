<?php

namespace App\EventListener;

use App\Entity\CategoryProfessional;
use App\Entity\User;
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
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof CategoryProfessional && $action === 'persist') 
            $entity->setSlug($this->context->slug($entity->getName()));
        
        if($entity instanceof User)
            $entity->setSlug($this->context->slug($entity));
    }
}