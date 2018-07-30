<?php

namespace AppBundle\Service;

use AppBundle\Entity\Following;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class MessageService
{
    private $em;
    private $requestStack;
    private $paginator;

    /**
     * MessageService constructor.
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
     * @param bool $sender
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findMessages(User $user, $sender)
    {
        $query = $this->getRepository()->findByUser($user, $sender);

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
     * @param User $receiver
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setReaded(User $receiver)
    {
        $messages = $this->getRepository()->findByReceiverAndReaded($receiver, false);

        /** @var Message $message */
        foreach ($messages as $message) {
            $message->setReaded(true);

            $this->saveMessage($message);
        }
    }

    /**
     * @param Message $message
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMessage(Message $message)
    {
        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * @param Message $message
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeFollowing(Message $message)
    {
        $this->em->remove($message);
        $this->em->flush();
    }

    /**
     * @return \AppBundle\Repository\MessageRepository|\Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->em->getRepository('AppBundle:Message');
    }
}