<?php

namespace App\Repository;

use App\Entity\Marketplace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Marketplace|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marketplace|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marketplace[]    findAll()
 * @method Marketplace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketplaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marketplace::class);
    }

    // /**
    //  * @return Marketplace[] Returns an array of Marketplace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Marketplace
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
