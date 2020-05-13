<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    // /**
    //  * @return Place[] Returns an array of Place objects
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


    public function findAvgRatePlace(): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT place.id, place.category_id, place.name, avg(user_feedback_place.rate) AS avgRate FROM place 
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
GROUP BY place.id ORDER BY avgRate DESC;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAvgRatePlaceByCategory($category_id): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT place.id, place.category_id, place.name, avg(user_feedback_place.rate) AS avgRate FROM place 
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
WHERE place.category_id = :category_id
GROUP BY place.id ORDER BY avgRate DESC;';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Place
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
