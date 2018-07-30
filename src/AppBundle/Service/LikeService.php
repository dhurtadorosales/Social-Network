<?php

namespace AppBundle\Service;


use AppBundle\Entity\Like;
use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class LikeService
{
    private $em;
    private $requestStack;
    private $paginator;

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
     * @param Publication $publication
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByPublication(Publication $publication)
    {
        return $this->getRepository()->findByPublication($publication);
    }

    /**
     * @param User $user
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllByUser(User $user)
    {
        $query = $this->getRepository()->findAllByUser($user);

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
     * @param Like $like
     *
     * @return bool
     */
    public function saveLike(Like $like)
    {
        $result = true;

        try {
            $this->em->persist($like);
            $this->em->flush();
        } catch (OptimisticLockException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param Like $like
     *
     * @return bool
     */
    public function removeLike(Like $like)
    {
        $result = true;

        try {
            $this->em->remove($like);
            $this->em->flush();
        } catch (OptimisticLockException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @return \AppBundle\Repository\LikeRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Like');
    }
}