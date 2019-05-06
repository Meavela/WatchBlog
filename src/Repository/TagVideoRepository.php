<?php

namespace App\Repository;

use App\Entity\TagVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TagVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagVideo[]    findAll()
 * @method TagVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagVideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TagVideo::class);
    }

    // /**
    //  * @return TagVideo[] Returns an array of TagVideo objects
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
    public function findOneBySomeField($value): ?TagVideo
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
