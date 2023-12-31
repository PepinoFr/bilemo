<?php

namespace App\Repository;

use App\Entity\Consumer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\WhereClause;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @extends ServiceEntityRepository<Consumer>
 *
 * @method Consumer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consumer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consumer[]    findAll()
 * @method Consumer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consumer::class);
    }

    public function add(Consumer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Consumer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getConsumerById(int $id) {
        $consumer = $this->find($id);
        if ($consumer == null) {
            throw new Exception("not found",404);
        }
        return $consumer;
    }
    public function findAllWithPagination($user,$page,$limit) {
        $qb = $this->createQueryBuilder('b')
            ->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit)
            ->where('b.client = ' .$user  );
        return $qb->getQuery()->getResult();
    }

    public function checkValidation(Consumer $consumer) {
        if (empty( $consumer->getName() )) {
            throw new Exception("name not found",404);
        }
        if (empty( $consumer->getFirstname() )) {
            throw new Exception("first name not found",400 );
        }
    }

//    /**
//     * @return Consumer[] Returns an array of Consumer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Consumer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
