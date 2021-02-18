<?php

namespace App\Repository;

use App\Entity\ChecklistItem;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    /** @return Collection<ChecklistItem> */
    public function findAllByTask(Task $task): Collection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(ChecklistItem::class, 'checklist');
        $selectClause = $rsm->generateSelectClause([
            'checklist' => 'sorted',
        ]);
        $sql = sprintf('WITH RECURSIVE `sorted` AS (
            SELECT `checklist`.*,
                   1 as `cte_level`
            FROM `checklists` AS `checklist`
            WHERE `checklist`.`follows` IS NULL
                AND `checklist`.`task` = :task
            UNION ALL
            SELECT `checklist`.*,
                   `sorted`.`cte_level` + 1 AS `cte_level`
            FROM `sorted`, `checklists` as `checklist`
            WHERE `checklist`.`follows` = `sorted`.`id`
        ) SELECT %s FROM `sorted` ORDER BY `cte_level`', $selectClause);
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('task', $task->getId()->toBinary(), ParameterType::BINARY);
        return new ArrayCollection($query->getResult());
    }
}
