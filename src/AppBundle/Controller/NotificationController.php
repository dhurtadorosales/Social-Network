<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class NotificationController extends Controller
{
    /**
     * @Route("/notifications", name="notifications")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function notificationsAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        $notificationsService = $this->get('notification.service');

        $notifications = $notificationsService->findByUser($user);

        //Read notifications
        $notificationsService->readNotifications($user);

        return $this->render(
            'AppBundle:Notification:notifications.html.twig',
            array(
                'user' => $user,
                'pagination' => $notifications
            )
        );
    }

    /**
     * @Route("/notifications/count", name="notifications_count")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNotificationsAction()
    {
        $nbNotifications = $this->get('notification.service')
            ->countNotReadedByUser($this->getUser());

        return new Response($nbNotifications);
    }

}
