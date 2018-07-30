<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function findByUser(User $user)
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }

    /**
     * @param User $user
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNotReadedByUser(User $user)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(n)')
            ->from('AppBundle:Notification', 'n')
            ->where('n.user = :user')
            ->andWhere('n.readed = :readed')
            ->setParameter('user', $user)
            ->setParameter('readed', false)
            ->getQuery()
            ->getSingleScalarResult();

        return $query;
    }
}