<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class LikeRepository extends EntityRepository
{
    /**
     * @param Publication $publication
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByPublication(Publication $publication)
    {
        $query = $this->createQueryBuilder('l')
            ->where('l.publication = :publication')
            ->setParameter('publication', $publication)
            ->getQuery()
            ->getSingleResult();

        return $query;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function findAllByUser(User $user)
    {
        $query = $this->createQueryBuilder('l')
            ->where('l.user = :user')
            ->setParameter('user', $user)
            ->orderBy('l.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }
}