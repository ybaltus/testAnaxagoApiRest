<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\ProjectInvestment;
use App\Entity\User;
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

    public function findByUserProject(User $user, Project $project)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.project = :project')
            ->setParameters(array('user'=>$user, 'project'=>$project))
            ->getQuery()
            ->getOneOrNullResult()
            ;
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
