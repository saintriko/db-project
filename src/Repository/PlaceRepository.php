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


    public function findAvgRatePlaceWithoutUserRate(): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT place.id AS placeId, place.category_id, place.name, avg(user_feedback_place.rate) AS avgRate FROM place
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
GROUP BY place.id ORDER BY avgRate DESC;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAvgRatePlaceByCategoryWithoutUserRate($category_id): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT place.id AS placeId, place.category_id, place.name, avg(user_feedback_place.rate) AS avgRate FROM place
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
WHERE place.category_id = :category_id
GROUP BY place.id ORDER BY avgRate DESC;';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    public function findAvgRatePlace($user_id): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'WITH PlaceAvg AS (
SELECT place.id as placeId, place.category_id, place.name, avg(user_feedback_place.rate)  AS avgRate FROM place
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
GROUP BY place.id ORDER BY avgRate DESC) 
SELECT * FROM PlaceAvg
LEFT JOIN user_feedback_place ON user_feedback_place.place_id = PlaceAvg.placeId AND user_feedback_place.user_id = :user_id;';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function findAvgRatePlaceByCategory($category_id, $user_id): array
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'WITH PlaceAvg AS (
SELECT place.id as placeId, place.category_id, place.name, avg(user_feedback_place.rate)  AS avgRate FROM place
LEFT JOIN user_feedback_place ON place.id = user_feedback_place.place_id
GROUP BY place.id ORDER BY avgRate DESC) 
SELECT * FROM PlaceAvg
LEFT JOIN user_feedback_place ON user_feedback_place.place_id = PlaceAvg.placeId AND user_feedback_place.user_id = :user_id
WHERE PlaceAvg.category_id = :category_id;';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['category_id' => $category_id, 'user_id' => $user_id]);
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
