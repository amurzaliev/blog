<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostRating;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostRating[]    findAll()
 * @method PostRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostRating::class);
    }

    public function findUserPostRating(User $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
