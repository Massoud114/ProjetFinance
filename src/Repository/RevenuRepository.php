<?php

namespace App\Repository;

use App\Entity\OperationSearch;
use App\Entity\Revenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Revenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revenu[]    findAll()
 * @method Revenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RevenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revenu::class);
    }

    // /**
    //  * @return Revenu[] Returns an array of Revenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Revenu
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
	public function findByPeriod(OperationSearch $search)
	{
		return $this->createQueryBuilder('r')
			->where('r.submit_at BETWEEN :period1 AND :period2')
			->setParameter('period1', $search->getFirstDate())
			->setParameter('period2', $search->getSecondDate())
			->getQuery()
			->getResult();
	}

	public function getMoyenne()
	{
		$date = new \DateTime();
		$date->sub(new \DateInterval('P1M'));
		return $this->createQueryBuilder('r')
			->select('AVG(r.amount) as moyenne')
			->where('r.submit_at > :date')
			->setParameter('date', $date)
			->getQuery()
			->getSingleScalarResult();
	}
}
