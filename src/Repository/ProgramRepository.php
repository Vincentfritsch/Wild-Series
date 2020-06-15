<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    // /**
    //  * @return Program[] Returns an array of Program objects
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

    public function findAllWithCategories()
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c' )
            ->addSelect('c')
            ->getQuery();

        return $qb->execute();
    }

    public function findAllWithCategoriesAndActors()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT a, c, t FROM App\Entity\Program a INNER JOIN a.category c LEFT JOIN a.actors t');

        return $query->execute();
    }
}
