<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function chatList($id){
        return $this->createQueryBuilder('p')
        ->where('p.participantA = :id ')
        ->orWhere('p.participantB = :id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult();
    }

    public function chatContent($chat){
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery(
        'SELECT m.content,m.sendDate, u.id from App\Entity\Message m, App\Entity\ChatMessage cm, App\Entity\Chat c, App\Entity\User u
        where c.id = :chatid and c.id = cm.Chat
        and m.chatMessage = cm.id
        and u.id = m.UserSend')
        ->setParameter('chatid', $chat);

        return $query->getResult();
    }


    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
