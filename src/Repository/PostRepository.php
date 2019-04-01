<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getLastPostsQuery()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'desc')
            ->getQuery();
    }

    public function getLastPostsByUserQuery(User $user)
    {
        return $this->createQueryBuilder('p')
            ->where('p.author = :author')
            ->setParameter('author', $user)
            ->orderBy('p.createdAt', 'desc')
            ->getQuery();
    }
}
