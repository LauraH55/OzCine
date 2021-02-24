<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

     /**
     * Find all movies ordered by title ASC
     * 
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitleAsc()
    {
        return $this->createQueryBuilder('movie')
            ->orderBy('movie.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find all movies ordered by title ASC en DQL
     * 
     * @return Movie[] Returns an array of Movie objects
     */
    public function findAllOrderedByTitleAscDql()
    {
        // La requête en DQL s'exécute avec le Manager
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT m
            FROM App\Entity\Movie m
            ORDER BY m.title ASC'
        );
        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Movie
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
