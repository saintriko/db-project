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

}
