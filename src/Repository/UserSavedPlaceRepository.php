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

}
