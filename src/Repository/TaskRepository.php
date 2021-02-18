<?php

namespace App\Repository;

use App\Entity\Heading;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /** @return Collection<Task> */
    public function findAllByHeading(Heading $heading): Collection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Task::class, 'task');
        $selectClause = $rsm->generateSelectClause([
            'task' => 'sorted',
        ]);
        $sql = sprintf('WITH RECURSIVE `sorted` AS (
            SELECT `task`.*,
                   1 as `cte_level`
            FROM `tasks` AS `task`
            WHERE `task`.`follows` IS NULL
                AND `task`.`heading` = :heading
            UNION ALL
            SELECT `task`.*,
                   `sorted`.`cte_level` + 1 AS `cte_level`
            FROM `sorted`, `tasks` as `task`
            WHERE `task`.`follows` = `sorted`.`id`
        ) SELECT %s FROM `sorted` ORDER BY `cte_level`', $selectClause);
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('heading', $heading->getId()->toBinary(), ParameterType::BINARY);
        return new ArrayCollection($query->getResult());
    }
}
