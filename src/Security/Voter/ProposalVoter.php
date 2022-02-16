<?php

namespace App\Security\Voter;

use App\Entity\Proposal;
use App\Entity\User;
use App\Service\ContextService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProposalVoter extends Voter
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
            && $subject instanceof Proposal;
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

    private function canView(Proposal $proposal, User $user): bool
    {
        if ($this->security->isGranted(self::VIEW, $user)) 
            return $proposal->getProfessional()->getUser() === $user;
        else 
            return false;
    }

    private function canEdit(Proposal $proposal, User $user): bool
    {
        return $this->canView($proposal, $user);
    }

    private function canDel(Proposal $proposal, User $user): bool
    {
        return $this->canView($proposal, $user);
    }
}
