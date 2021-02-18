<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Heading;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    /** @return Collection<Heading> */
    public function findAllByProject(Project $project): Collection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Heading::class, 'heading');
        $selectClause = $rsm->generateSelectClause([
            'heading' => 'sorted',
        ]);
        $sql = sprintf('WITH RECURSIVE `sorted` AS (
            SELECT `heading`.*,
                   1 as `cte_level`
            FROM `headings` AS `heading`
            WHERE `heading`.`follows` IS NULL
                AND `heading`.`project` = :project
            UNION ALL
            SELECT `heading`.*,
                   `sorted`.`cte_level` + 1 AS `cte_level`
            FROM `sorted`, `headings` as `heading`
            WHERE `heading`.`follows` = `sorted`.`id`
        ) SELECT %s FROM `sorted` ORDER BY `cte_level`', $selectClause);
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('project', $project->getId()->toBinary(), ParameterType::BINARY);
        return new ArrayCollection($query->getResult());
    }
}
