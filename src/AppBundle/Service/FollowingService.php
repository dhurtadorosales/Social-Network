<?php

namespace AppBundle\Service;

use AppBundle\Entity\Following;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class FollowingService
{
    private $em;
    private $requestStack;
    private $paginator;

    /**
     * FollowingService constructor.
     *
     * @param EntityManager $em
     * @param RequestStack $requestStack
     * @param Paginator $paginator
     */
    public function __construct(
        EntityManager $em,
        RequestStack $requestStack,
        Paginator $paginator
    )
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }

    /**
     * @param $following
     * @param $followed
     *
     * @return Following|null|object
     */
    public function findOneUserByFollowingAndFollowed($following, $followed)
    {
        return $this->getRepository()->findOneBy(
            array(
                'following' => $following,
                'followed' => $followed
            )
        );
    }

    /**
     * @param User $user
     * @param $followType
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findByUser(User $user, $followType)
    {
        $query = $this->getRepository()->findByUser($user, $followType);

        $pagination = $this->paginator->paginate(
            $query,
            $this->requestStack->getCurrentRequest()->query->getInt(
                'page',
                1
            ),
            5
        );

        return $pagination;
    }

    /**
     * @param Following $following
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveFollowing(Following $following)
    {
        $this->em->persist($following);
        $this->em->flush();
    }

    /**
     * @param Following $following
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeFollowing(Following $following)
    {
        $this->em->remove($following);
        $this->em->flush();
    }

    /**
     * @return \AppBundle\Repository\FollowingRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Following');
    }
}