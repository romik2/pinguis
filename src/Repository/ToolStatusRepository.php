<?php

namespace App\Repository;

use App\Entity\ToolStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToolStatus>
 *
 * @method ToolStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToolStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToolStatus[]    findAll()
 * @method ToolStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToolStatus::class);
    }

    public function add(ToolStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ToolStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getToolsStatus(array $tools, $limit = 1): array
    {
        $qb = $this->createQueryBuilder('ts');
        $toolsIds = [];
        $startAt = new \DateTime();
        $endAt = (clone $startAt)->modify("-{$limit}minute");

        foreach ($tools as $tool) {
            $toolsIds[] = $tool->getId();
        }

        $qb
            ->select('ts')
            ->andWhere($qb->expr()->in('ts.tool', ':toolId'))
            ->setParameter('toolId', $toolsIds)
            ->andWhere($qb->expr()->between('ts.createdAt', ':endAt', ':startAt'))
            ->setParameter('startAt', $startAt)
            ->setParameter('endAt', $endAt);

        $result = [];
        foreach ($qb->getQuery()->getResult() as $toolStatus) {
            $result[$toolStatus->getTool()->getId()][] = $toolStatus;
        }

        return $result;
    }
}
