<?php

namespace App\Repository;

use App\Entity\WorkTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkTime[]    findAll()
 * @method WorkTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkTime::class);
    }

}
