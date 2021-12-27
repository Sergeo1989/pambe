<?php

namespace App\Security\Voter;

use App\Entity\Qualification;
use App\Entity\User;
use App\Service\ContextService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QualificationVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DEL = 'delete';

    private $context;

    public function __construct(ContextService $context)
    {
        $this->context = $context;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DEL])
            && $subject instanceof Qualification;
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

    private function canView(Qualification $qualification, User $user): bool
    {
        if ($this->context->hasRole("ROLE_USER")) 
            return $user->getProfessional() === $qualification->getProfessional();
        else 
            return false;
    }

    private function canEdit(Qualification $qualification, User $user): bool
    {
        return $this->canView($qualification, $user);
    }

    private function canDel(Qualification $qualification, User $user): bool
    {
        return $this->canView($qualification, $user);
    }
}
