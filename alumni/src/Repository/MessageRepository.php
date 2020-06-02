<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findChattedSender($user){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT uB
            FROM App\Entity\User uA, App\Entity\User uB, App\Entity\Message m
            WHERE uA.id = m.UserSend
            
            AND uA.id = :userid'
        )->setParameter('userid', $user);

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findChattedReceiver($user){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
        'select uA
        FROM App\Entity\User uA, App\Entity\User uB, App\Entity\Message m
        where uA.id = m.UserSend
       
        and uB.id = :userid'
        )->setParameter('userid', $user);
        return $query->getResult();

    }

    public function chatMessageSend($userA, $userB){
        return $this->createQueryBuilder('m')
        #->where('m.UserReceive = :idreceive')
        ->andwhere('m.UserSend = :idsend')
        ->setParameter('idreceive', $userA)
        ->setParameter('idsend', $userB)
        ->orderBy('m.sendDate','DESC')
        ->getQuery()
        ->getResult();
    }

    public function chatMessageReceive($userA, $userB){
        return $this->createQueryBuilder('m')
        #->where('m.UserReceive = :idreceive')
        ->andwhere('m.UserSend = :idsend')
        ->setParameter('idreceive', $userB)
        ->setParameter('idsend', $userA)
        ->orderBy('m.sendDate','DESC')
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
