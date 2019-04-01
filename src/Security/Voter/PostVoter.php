<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    public const EDIT   = 'edit';
    public const REMOVE = 'remove';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::EDIT, self::REMOVE])
            && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->isPostOwner($subject, $user);
                break;
            case self::REMOVE:
                return $this->isPostOwner($subject, $user);
                break;
        }

        return false;
    }

    private function isPostOwner(Post $post, User $user)
    {
        return $user === $post->getAuthor();
    }
}
