<?php

namespace App\Repository;

use App\Entity\Depense;
use App\Entity\OperationSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depense[]    findAll()
 * @method Depense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depense::class);
    }

    // /**
    //  * @return Depense[] Returns an array of Depense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Depense
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


	public function findByPeriod(OperationSearch $search)
	{
		return $this->createQueryBuilder('d')
			->where('d.submit_at BETWEEN :period1 AND :period2')
			->setParameter('period1', $search->getFirstDate())
			->setParameter('period2', $search->getSecondDate())
			->getQuery()
			->getResult();
	}

	public function getMoyenne()
	{
		$date = new \DateTime();
		$date->sub(new \DateInterval('P1M'));
		return $this->createQueryBuilder('d')
			->select('AVG(d.amount) as moyenne')
			->where('d.submit_at > :date')
			->setParameter('date', $date)
			->getQuery()
			->getSingleScalarResult();
	}

	public function getSumOfDepense()
	{
		return $this->createQueryBuilder('d')
			->select("SUM(d.amount) as totalDepense")
			->getQuery()
			->getSingleScalarResult();
	}
}
