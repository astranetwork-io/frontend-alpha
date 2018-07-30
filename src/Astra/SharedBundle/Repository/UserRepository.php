<?php

namespace Astra\SharedBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByWord(string $word, $limit = 0, $filtedUsers = [])
    {
        $qbBuilser = $this->createQueryBuilder('u')->
        where(
            "
            (u.username LIKE :word)
            OR (u.email LIKE :word)
            OR (u.name LIKE :word)
            OR (u.surname LIKE :word)
            OR (u.skype LIKE :word)
            OR (u.phone LIKE :word)
            OR (u.status LIKE :word)
            "
        )->setParameter('word', "%{$word}%");

        if(!empty($filtedUsers))
        {
            $qbBuilser->andWhere("(u.id NOT IN (:users))")->setParameter('users',$filtedUsers);
        }

        if($limit) $qbBuilser->setMaxResults($limit);

        $qb = $qbBuilser->getQuery();
        return $qb->execute();
    }
}
