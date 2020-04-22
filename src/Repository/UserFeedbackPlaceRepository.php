<?php

namespace App\Repository;

use App\Entity\UserFeedbackPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFeedbackPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFeedbackPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFeedbackPlace[]    findAll()
 * @method UserFeedbackPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFeedbackPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFeedbackPlace::class);
    }

    // /**
    //  * @return UserFeedbackPlace[] Returns an array of UserFeedbackPlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFeedbackPlace
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
