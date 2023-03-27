<?php

 
namespace Aldaflux\GameQuizzBundle\Repository;


use Aldaflux\GameQuizzBundle\Entity\Question;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;

 
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }
    /*
    public function findAll()
    {
        $qb = $this->createQueryBuilder('q');
        return $qb->orderBy('g.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
     
    }
    
    */
    
}
