<?php

namespace TTT\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TTT\Infrastructure\Doctrine\Entity\GameSession;

/**
 * @extends ServiceEntityRepository<GameSession>
 *
 * @method GameSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameSession|null findOneBy(array $criteria, array $orderBy = null)
 * @psalm-method list<GameSession> findAll()
 * @method GameSession[]    findAll()
 * @psalm-method list<GameSession> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method GameSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameSession::class);
    }

    public function add(GameSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GameSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return GameSession[] Returns an array of GameSession objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GameSession
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
