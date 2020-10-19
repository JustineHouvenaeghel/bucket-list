<?php

namespace App\Repository;

use App\Entity\IdeaUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdeaUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdeaUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdeaUser[]    findAll()
 * @method IdeaUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdeaUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdeaUser::class);
    }

    public function findIdeasInMyList($user)
    {
        $qb = $this->createQueryBuilder('ui')
            ->where('ui.user = :user')
            ->setParameter('user', $user)
            ->join('ui.idea', 'idea')
            ->orderBy('ui.dateAdded', 'DESC')
        ;

        return $qb;
    }
}
