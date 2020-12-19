<?php

namespace App\Repository;

use App\Entity\Revenu;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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

	/**
	 * @param UserInterface $user
	 * @return Query Return the query for latest revenu related to a user
	 */
	public function getLatestQuery(UserInterface $user)
	{
		$week = new DateTime('today');
		$week->sub(new DateInterval('P7D'));
		return $this->createQueryBuilder('r')
			->where('r.User = :user')
			->andWhere('r.submit_at > :week')
			->setParameter('user', $user)
			->setParameter('week', $week)
			->getQuery();

	}
}
