<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const EDIT   = 'edit';
    public const REMOVE = 'remove';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::REMOVE, self::EDIT])
            && $subject instanceof Comment;
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
                return $this->isCommentOwner($subject, $user);
                break;
            case self::REMOVE:
                return $this->isCommentOwner($subject, $user);
                break;
        }

        return false;
    }

    private function isCommentOwner(Comment $comment, User $user)
    {
        return $user === $comment->getAuthor();
    }
}
