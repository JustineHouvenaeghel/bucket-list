<?php

namespace App\Repository;

use App\Entity\Idea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Idea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idea[]    findAll()
 * @method Idea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdeaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idea::class);
    }
    public function findListIdeasWithCategories($filter = '')
    {
        $qb = $this->createQueryBuilder('i')
            ->addSelect('c')
            ->where('i.isPublished = true');
        if ($filter) {
            $qb->where('c = :filter')
                ->setParameter('filter', $filter);
        };

        $qb->join('i.category', 'c')
            ->orderBy('i.dateCreated', 'DESC')
            ;

        return $qb;
    }

}
