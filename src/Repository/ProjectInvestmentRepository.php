<?php

namespace App\Repository;

use App\Entity\ProjectInvestment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProjectInvestment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectInvestment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectInvestment[]    findAll()
 * @method ProjectInvestment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectInvestmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectInvestment::class);
    }

    // /**
    //  * @return ProjectInvestment[] Returns an array of ProjectInvestment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProjectInvestment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
