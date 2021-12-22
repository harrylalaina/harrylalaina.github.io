<?php

namespace App\Repository;

use App\Entity\NearMiss;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NearMiss|null find($id, $lockMode = null, $lockVersion = null)
 * @method NearMiss|null findOneBy(array $criteria, array $orderBy = null)
 * @method NearMiss[]    findAll()
 * @method NearMiss[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NearMissRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NearMiss::class);
    }

    /**
     * return nombre de nearmiss par semaine
     */
    public function countByDate()
    {
        //$query = $this->getEntityManager()->createQuery("
        //    SELECT SUBSTRING('n.created_at', 1, 10) as dateNearmiss,
        //    COUNT(n) as count FROM App\Entity\NearMiss n GROUP BY dateNearmiss
        //");
        //return $query->getResult();

        $query = $this->createQueryBuilder('n')
            ->select("n.week as dateNearmiss, COUNT(n) as count")
            //->select('COUNT(n) as count')
            ->groupBy('dateNearmiss');
        return $query->getQuery()->getResult();
    }

    /**
     * return nombre de near miss par Employe
     */
    public function countNearmissEmploye()
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT em.name as nomEmploye, COUNT(n) as count FROM App\Entity\NearMiss n
            JOIN App\Entity\Employe em WHERE em.id = n.employe
            GROUP BY nomEmploye
        ");
        return $query->getResult();
    }


    public function nearmissByWeek($from)
    {
        $query = $this->createQueryBuilder('n')
            //->select('COUNT(n) as count')
            ->where('n.week = :from')
            ->setParameter(':from', $from);
        return $query->getQuery()->getResult();
    }

    public function countNearmiss()
    {
        $query = $this->createQueryBuilder('near_miss');
        $query->addSelect('COUNT(near_miss) as count')
            ->groupBy('near_miss.week')
            ->orderBy('near_miss.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(30);
        return $query->getQuery()->getResult();
    }

    /**
     * Return near miss between 2 dates
     */
    public function selectInterval($from, $to)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT n FROM App\Entity\NearMiss n WHERE n.createdAt > :from AND n.createdAt < :to
        ")
            ->setParameter(':from', $from)
            ->setParameter(':to', $to);
        return $query->getResult();
    }

    public function traitementClosetAt($from)
    {
        $query = $this->createQueryBuilder('n')
            ->where('SUBSTRING(n.closedAt,1,10) = :from')
            ->setParameter(':from', $from);
        return $query->getQuery()->getResult();
    }
}
