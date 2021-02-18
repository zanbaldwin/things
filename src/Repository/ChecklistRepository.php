<?php

namespace App\Repository;

use App\Entity\ChecklistItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChecklistItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChecklistItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChecklistItem[]    findAll()
 * @method ChecklistItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChecklistItem::class);
    }
}
