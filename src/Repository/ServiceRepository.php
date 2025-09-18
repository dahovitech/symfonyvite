<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Find all published services with their translations
     */
    public function findPublishedServices(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.translations', 't')
            ->leftJoin('t.language', 'l')
            ->andWhere('s.isPublished = :published')
            ->andWhere('l.isActive = :active')
            ->setParameter('published', true)
            ->setParameter('active', true)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find service by slug with translations
     */
    public function findBySlugWithTranslations(string $slug): ?Service
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.translations', 't')
            ->leftJoin('t.language', 'l')
            ->andWhere('s.slug = :slug')
            ->andWhere('s.isPublished = :published')
            ->andWhere('l.isActive = :active')
            ->setParameter('slug', $slug)
            ->setParameter('published', true)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
