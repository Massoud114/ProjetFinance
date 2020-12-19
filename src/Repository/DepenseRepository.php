<?php

namespace App\Repository;

use App\Entity\Depense;
use App\Entity\OperationSearch;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

	/**
	 * @param UserInterface $user
	 * @return Query Returns a query of depenses related to a user
	 */
	public function getLatestQuery(UserInterface $user)
	{
		$week = new DateTime('today');
		$week->sub(new DateInterval('P7D'));
		return $this->createQueryBuilder('d')
			->where('d.User = :user')
			->andWhere('d.submit_at > :week')
			->setParameter('user', $user)
			->setParameter('week', $week)
			->getQuery();
	}

}
