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
    public function countByDate($id)
    {
        //$query = $this->getEntityManager()->createQuery("
        //    SELECT SUBSTRING('n.created_at', 1, 10) as dateNearmiss,
        //    COUNT(n) as count FROM App\Entity\NearMiss n GROUP BY dateNearmiss
        //");
        //return $query->getResult();

        $query = $this->createQueryBuilder('n')
            ->select("n.week as dateNearmiss, COUNT(n) as count")
            ->where('n.year = :id')
            ->groupBy('dateNearmiss')
            ->setParameter(':id', $id);
        return $query->getQuery()->getResult();
    }

    /**
     * return nombre de near miss par Employe
     */
    public function countNearmissEmploye($id)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT em.name as nomEmploye, COUNT(n) as count FROM App\Entity\NearMiss n
            JOIN App\Entity\Employe em WHERE em.id = n.employe AND n.year = :id
            GROUP BY nomEmploye
        ")
            ->setParameter(':id', $id);
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

    /**
     * Pour la pagination 
     */
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

    /**
     * Return time of traitment of nearmiss
     */
    public function traitementClosetAt($from)
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.closedAt < :from')
            ->setParameter(':from', $from);
        return $query->getQuery()->getResult();
    }

    /**
     * Return nearmiss per year
     */
    public function cycleNearmiss($id)
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.year = :id')
            ->orderBy('n.createdAt', 'DESC')
            ->setParameter(':id', $id);
        return $query->getQuery()->getResult();
    }

    /**
     * Recherche par mot cle
     */
    public function searchFullText($mots = null, $employe = null, $categorie = null, $year = null)
    {
        $query = $this->createQueryBuilder('n');
        if ($mots != null) {
            $query->andwhere('MATCH_AGAINST(n.titre, n.description) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if ($employe != null) {
            $query->leftJoin('n.employe', 'em')
                ->andwhere('em.id = :id')
                ->setParameter('id', $employe);
        }
        if ($categorie != null) {
            $query->leftJoin('n.categorie', 'cat')
                ->andWhere('cat.id = :id1')
                ->setParameter('id1', $categorie);
        }
        if ($year != null) {
            $query->leftJoin('n.year', 'y')
                ->andWhere('y.id = :id2')
                ->setParameter('id2', $year);
        }
        return $query->getQuery()->getResult();
    }
}
