<?php

namespace App\Repository;

use App\Entity\ToolType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToolType>
 *
 * @method ToolType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToolType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToolType[]    findAll()
 * @method ToolType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToolType::class);
    }

    public function add(ToolType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ToolType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
