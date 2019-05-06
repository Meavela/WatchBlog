<?php

namespace App\Repository;

use App\Entity\TypeVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVideo[]    findAll()
 * @method TypeVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeVideo::class);
    }

    // /**
    //  * @return TypeVideo[] Returns an array of TypeVideo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeVideo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
