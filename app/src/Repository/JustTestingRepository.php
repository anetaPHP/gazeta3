<?php
/**
 * JustTesting not used.
 */

namespace App\Repository;

use App\Entity\JustTesting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class JustTestingRepository
 * @package App\Repository
 * @method JustTesting|null find($id, $lockMode = null, $lockVersion = null)
 * @method JustTesting|null findOneBy(array $criteria, array $orderBy = null)
 * @method JustTesting[]    findAll()
 * @method JustTesting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JustTestingRepository extends ServiceEntityRepository
{
    /**
     * JustTestingRepository constructor.
     * @param ManagerRegistry $registry
     *
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JustTesting::class);
    }

    // /**
    //  * @return JustTesting[] Returns an array of JustTesting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JustTesting
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
