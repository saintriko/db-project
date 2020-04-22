<?php

namespace App\Repository;

use App\Entity\UserSavedPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSavedPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSavedPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSavedPlace[]    findAll()
 * @method UserSavedPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSavedPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSavedPlace::class);
    }

    // /**
    //  * @return UserSavedPlace[] Returns an array of UserSavedPlace objects
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
    public function findOneBySomeField($value): ?UserSavedPlace
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
