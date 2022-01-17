<?php

namespace App\Repository;

use App\Entity\Colleague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Colleague|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colleague|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colleague[]    findAll()
 * @method Colleague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColleagueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Colleague::class);
    }

    public function checkIfEmailExists($email)
    {
        $q = $this->createQueryBuilder('p');
        $q->select('p.email')->where('p.email = '.$email);
        return $q->getQuery()->getResult();
    }
}