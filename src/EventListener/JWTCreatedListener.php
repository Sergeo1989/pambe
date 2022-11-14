<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class JWTCreatedListener
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!($user instanceof UserInterface)) {
            return;
        }

        if ($user instanceof User) {
            $isWorker = $user->getProfessional() ? true : false;
            $image = $user->getProfile();
            $profile = !$image ? false : ($this->storage->resolveUri($image, 'imageFile') ?? false);
            
            $data['user'] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'isWorker' => $isWorker,
                'profile' => $profile,
                'professional' => $isWorker ? $user->getProfessional()->getId() : false
            ];
        }

        $event->setData($data);
    }
}