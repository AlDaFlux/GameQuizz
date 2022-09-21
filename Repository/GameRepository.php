<?php

 
namespace Aldaflux\GameQuizzBundle\Repository;


use Aldaflux\GameQuizzBundle\Entity\Game;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
 
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findDefault()
    {
        $qb = $this->createQueryBuilder('g');
        return $qb->orderBy('g.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
     
    }
}
