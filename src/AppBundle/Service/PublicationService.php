<?php

namespace AppBundle\Service;

use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class PublicationService
{
    private $em;
    private $requestStack;
    private $paginator;

    /**
     * PublicationService constructor.
     *
     * @param EntityManager $em
     * @param RequestStack $requestStack
     * @param Paginator $paginator
     */
    public function __construct(EntityManager $em, RequestStack $requestStack, Paginator $paginator)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }

    /**
     * @param User $user
     * @param bool $follow
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllByUser(User $user, $follow)
    {
        if ($follow) {
            $following = $this->em->getRepository('AppBundle:Following')
                ->findBy(
                    array(
                        'following' => $user
                    )
                );

            $followingList = array();
            foreach ($following as $follow) {
                $followingList[] = $follow->getFollowed();
            }

            $query = $this->getRepository()->findAllByUser($user, $followingList);
        } else {
            $query = $this->getRepository()->findAllByUser($user);
        }

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
     * @param Publication $publication
     *
     * @return bool
     */
    public function removePublication(Publication $publication)
    {
        $result = true;
        $this->em->remove($publication);
        try {
            $this->em->flush();
        } catch (OptimisticLockException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param Publication $publication
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function savePublication(Publication $publication)
    {
        $this->em->persist($publication);
        $this->em->flush();
    }

    /**
     * @return \AppBundle\Repository\PublicationRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Publication');
    }
}