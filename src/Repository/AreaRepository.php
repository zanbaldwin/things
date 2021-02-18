<?php

namespace App\Repository;

use App\Entity\Area;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Area|null find($id, $lockMode = null, $lockVersion = null)
 * @method Area|null findOneBy(array $criteria, array $orderBy = null)
 * @method Area[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Area::class);
    }

    /** @return Collection<Area> */
    public function findAll(): Collection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Area::class, 'area');
        $selectClause = $rsm->generateSelectClause([
            'area' => 'sorted',
        ]);
        $sql = sprintf('WITH RECURSIVE `sorted` AS (
            SELECT `area`.*,
                   1 as `cte_level`
            FROM `areas` AS `area`
            WHERE `area`.`follows` IS NULL
            UNION ALL
            SELECT `area`.*,
                   `sorted`.`cte_level` + 1 AS `cte_level`
            FROM `sorted`, `areas` as `area`
            WHERE `area`.`follows` = `sorted`.`id`
        ) SELECT %s FROM `sorted` ORDER BY `cte_level`', $selectClause);
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return new ArrayCollection($query->getResult());
    }
}
