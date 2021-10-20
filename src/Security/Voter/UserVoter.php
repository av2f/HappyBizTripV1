<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const VIEW = 'profile_view';
    const EDIT = 'profile_edit';
    const DELETE = 'profile_delete';

    protected function supports(string $attribute, $profile): bool
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $profile instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $profile, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // the logic of this voter is pretty simple: if the logged user is the same
        // who wants to do action, grant permission; otherwise, deny it.
        return $profile->getId() === $user->getId();
    }
}
