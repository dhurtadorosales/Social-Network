<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{

    /**
     * @param User $user
     * @param bool $sender
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByUser(User $user, $sender)
    {
        $query = $this->createQueryBuilder('m');

        if ($sender) {
            $query
                ->where('m.sender = :user');
        } else {
            $query
                ->where('m.receiver = :user');
        }

        $query
            ->setParameter('user', $user)
            ->orderBy('m.id', 'DESC')
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
            ->select('COUNT(m)')
            ->from('AppBundle:Message', 'm')
            ->where('m.receiver = :user')
            ->andWhere('m.readed = :readed')
            ->setParameter('user', $user)
            ->setParameter('readed', false)
            ->getQuery()
            ->getSingleScalarResult();

        return $query;
    }

    /**
     * @param User $receiver
     * @param $readed
     *
     * @return array
     */
    public function findByReceiverAndReaded(User $receiver, $readed)
    {
        $query = $this->createQueryBuilder('m')
            ->where('m.receiver = :receiver')
            ->andWhere('m.readed = :readed')
            ->setParameter('receiver', $receiver)
            ->setParameter('readed', $readed)
            ->getQuery()
            ->getResult();

        return $query;
    }
}