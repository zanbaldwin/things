<?php

namespace App\Repository;

use App\Entity\Area;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /** @return Collection<Project> */
    public function findAllByArea(Area $area): Collection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Project::class, 'project');
        $selectClause = $rsm->generateSelectClause([
            'project' => 'sorted',
        ]);
        $sql = sprintf('WITH RECURSIVE `sorted` AS (
            SELECT `project`.*,
                   1 as `cte_level`
            FROM `projects` AS `project`
            WHERE `project`.`follows` IS NULL
                AND `project`.`area` = :area
            UNION ALL
            SELECT `project`.*,
                   `sorted`.`cte_level` + 1 AS `cte_level`
            FROM `sorted`, `projects` as `project`
            WHERE `project`.`follows` = `sorted`.`id`
        ) SELECT %s FROM `sorted` ORDER BY `cte_level`', $selectClause);
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('area', $area->getId()->toBinary(), ParameterType::BINARY);
        return new ArrayCollection($query->getResult());
    }
}
