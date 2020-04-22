<?php

namespace App\Repository;

use App\Entity\PlaceHasService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlaceHasService|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlaceHasService|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlaceHasService[]    findAll()
 * @method PlaceHasService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceHasServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaceHasService::class);
    }

    // /**
    //  * @return PlaceHasService[] Returns an array of PlaceHasService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlaceHasService
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
