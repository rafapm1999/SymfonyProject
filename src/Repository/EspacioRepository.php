<?php

namespace App\Repository;

use App\Entity\Espacio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Espacio>
 *
 * @method Espacio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espacio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espacio[]    findAll()
 * @method Espacio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspacioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espacio::class);
    }

    public function getQueryAll()
    {
        $qb = $this->createQueryBuilder('e');
        return $qb->getQuery();
    }

    public function add(Espacio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function remove(Espacio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    //    /**
    //     * @return Espacio[] Returns an array of Espacio objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Espacio
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
