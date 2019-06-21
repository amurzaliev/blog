<?php

namespace App\Repository;

use App\Entity\ReferralVisitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReferralVisitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferralVisitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferralVisitor[]    findAll()
 * @method ReferralVisitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferralVisitorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReferralVisitor::class);
    }

    // /**
    //  * @return ReferralVisitor[] Returns an array of ReferralVisitor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReferralVisitor
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
