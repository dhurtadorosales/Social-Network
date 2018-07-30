<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class FollowingRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param $followType
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByUser(User $user, $followType)
    {
        $query = $this->createQueryBuilder('f');

        if ($followType == 'following') {
            $query
                ->where('f.following = :user');
        } else {
            $query
                ->where('f.followed = :user');
        }

        $query
            ->setParameter('user', $user)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }
}