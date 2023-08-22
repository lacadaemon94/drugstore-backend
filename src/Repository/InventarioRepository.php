<?php

namespace App\Repository;

use App\Entity\Inventario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventario>
 *
 * @method Inventario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inventario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inventario[]    findAll()
 * @method Inventario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventario::class);
    }

//    /**
//     * @return Inventario[] Returns an array of Inventario objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Inventario
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
