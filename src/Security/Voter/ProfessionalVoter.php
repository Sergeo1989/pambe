<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Service\ContextService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfessionalVoter extends Voter
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
            && $subject instanceof User;
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
                return $this->canEdit($subject);
            case self::VIEW:
                return $this->canView($subject);
            case self::DEL:
                return $this->canDel($subject);
        }

        return false;
    }

    private function canView(User $user): bool
    {
        return !is_null($user->getProfessional());
    }

    private function canEdit(User $user): bool
    {
        return $this->canView($user);
    }

    private function canDel(User $user): bool
    {
        return $this->canView($user);
    }
}
