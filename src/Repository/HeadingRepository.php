<?php

namespace App\Repository;

use App\Entity\Heading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Heading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Heading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Heading[]    findAll()
 * @method Heading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Heading::class);
    }
}
