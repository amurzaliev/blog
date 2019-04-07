<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getLastCommentsByUserQuery(User $user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :author')
            ->andWhere('c.deleted = false')
            ->setParameter('author', $user)
            ->orderBy('c.createdAt', 'desc')
            ->getQuery();
    }

    public function getPostsByPost(Post $post)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.post = :postId')
            ->setParameter('postId', $post)
            ->andWhere('c.deleted = false')
            ->orderBy('c.createdAt', 'desc')
            ->getQuery()
            ->getArrayResult();
    }
}
