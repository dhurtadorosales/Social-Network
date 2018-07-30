<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService
{
    private $em;

    /**
     * UserService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return User|null|object
     */
    public function findOneUserById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param $nick
     *
     * @return mixed
     */
    public function findOneByNick($nick)
    {
        return $this->getRepository()->findOneByNick($nick);
    }

    /**
     * @return User[]|array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param $search
     *
     * @return array
     */
    public function findBySearch($search)
    {
        return $this->getRepository()->findBySearch($search);
    }

    /**
     * @param User $user
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @return \AppBundle\Repository\UserRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:User');
    }
}