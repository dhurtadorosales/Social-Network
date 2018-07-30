<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Following;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param $nick
     *
     * @return array
     */
    public function findOneByNick($nick)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.nick = :nick')
            ->setParameter('nick', $nick)
            ->getQuery()
            ->getResult();

        return $query;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.id')
            ->getQuery()
            ->getResult();

        return $query;
    }

    /**
     * @param $search
     *
     * @return array
     */
    public function findBySearch($search)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.firstName LIKE :search')
            ->orWhere('u.lastName LIKE :search')
            ->orWhere('u.nick LIKE :search')
            ->setParameter('search', "%$search%")
            ->orderBy('u.id')
            ->getQuery()
            ->getResult();

        return $query;
    }

    /**
     * @param User $user
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findFollowingUsers(User $user)
    {
        $following = $this->getEntityManager()->getRepository('AppBundle:Following')
            ->findBy(
                array(
                    'following' => $user
                )
            );

        $followingList = array();

        /** @var Following $follow */
        foreach ($following as $follow) {
            $followingList[] = $follow->getFollowed();
        }

        $users = $this->createQueryBuilder('u')
            ->where('u.id != :followed')
            ->andWhere('u.id IN (:following)')
            ->setParameter('followed', $user->getId())
            ->setParameter('following', $followingList)
            ->orderBy('u.id', 'DESC');

        return $users;
    }
}