<?php

namespace App\Security\Voter;

use App\Entity\Professional;
use App\Entity\User;
use App\Service\ContextService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DEL = 'delete';

    private $context;
    private $security;

    public function __construct(ContextService $context, Security $security)
    {
        $this->context = $context;
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DEL])
            && $subject instanceof Professional;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if ($this->context->isAdmin()) {
            return true;
        }

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::VIEW:
                return $this->canView($subject, $user);
            case self::DEL:
                return $this->canDel($subject, $user);
        }

        return false;
    }

    private function canView(Professional $professional, User $user): bool
    {
        if ($this->security->isGranted(self::VIEW, $user)) 
            return $user->getProfessional() === $professional;
        else 
            return false;
    }

    private function canEdit(Professional $professional, User $user): bool
    {
        return $this->canView($professional, $user);
    }

    private function canDel(Professional $professional, User $user): bool
    {
        return $this->canView($professional, $user);
    }
}
