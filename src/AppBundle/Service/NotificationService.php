<?php

namespace AppBundle\Service;


use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class NotificationService
{
    private $em;
    private $requestStack;
    private $paginator;

    /**
     * NotificationService constructor.
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
     * @param User $user
     * @param $type
     * @param $typeId
     * @param null $extra
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setNotification(User $user, $type, $typeId, $extra = null)
    {
        $notification = new Notification();
        $notification
            ->setUser($user)
            ->setType($type)
            ->setTypeId($typeId)
            ->setReaded(false)
            ->setCreatedAt(new \DateTime('now'))
            ->setExtra($extra);

        $this->saveNotification($notification);
    }

    public function findByUser(User $user)
    {
        $query = $this->getRepository()->findByUser($user);

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
     * @param User $user
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNotReadedByUser(User $user)
    {
        return $this->getRepository()->countNotReadedByUser($user);
    }

    /**
     * @param User $user
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function readNotifications(User $user)
    {
        $query = $this->getRepository()->findByUser($user);

        /** @var Notification $notification */
        foreach ($query as $notification) {

            $notification->setReaded(true);

            $this->saveNotification($notification);
        }
    }

    /**
     * @param Notification $notification
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function saveNotification(Notification $notification)
    {
        $this->em->persist($notification);
        $this->em->flush();
    }

    /**
     * @return \AppBundle\Repository\NotificationRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Notification');
    }
}