<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Following;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class PublicationRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param Following $following
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllByUser(User $user, $following = null)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user);

        if ($following) {
            $query
                ->orWhere('p.user IN (:following)')
                ->setParameter('following', $following);
        }

        $query
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }

}