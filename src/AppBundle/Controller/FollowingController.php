<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Following;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FollowingController extends Controller
{
    /**
     * @Route("/follow", name="follow", methods={"POST"})
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function followAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $followedId = $request->get('followed');

        $followingService = $this->get('following.service');
        $userService = $this->get('user.service');
        $followed = $userService->findOneUserById($followedId);

        $following = new Following();
        $following
            ->setFollowing($user)
            ->setFollowed($followed);

        try {
            $followingService->saveFollowing($following);

            $status = 'Ahora estás siguiendo a ' . $followed;

            //Send notification
            $this->get('notification.service')
                ->setNotification(
                    $followed,
                    'follow',
                    $user->getId()
                );

        } catch (\Exception $exception) {
            $status = 'No se ha podido seguir a ' .$followed;
        }

        return new Response($status);
    }

    /**
     * @Route("/unfollow", name="unfollow", methods={"POST"})
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function unFollowAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $followedId = $request->get('followed');

        $followingService = $this->get('following.service');
        $following = $followingService->findOneUserByFollowingAndFollowed($user, $followedId);
        $followed = $following->getFollowed();

        try {
            $followingService->removeFollowing($following);

            $status = 'Has dejado de seguir a ' . $followed;

        } catch (\Exception $exception) {
            $status = 'No se han podido dejar de seguir a ' .$followed;
        }

        return new Response($status);
    }

    /**
     * @Route("/following/{user}", name="following_users")
     * @Route("/followed/{user}", name="followed_users")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function followingAction(Request $request, User $user)
    {
        if (!$user) {
            $user = $this->getUser();
        }

        $followType = 'following';
        if ($request->get('_route') == 'followed_users' ) {
            $followType = 'followed';
        }

        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home'));
        }

        $followings = $this->get('following.service')
            ->findByUser($user, $followType);

        return $this->render(
            'AppBundle:Following:following.html.twig',
            array(
                'type' => $followType,
                'profile_user' => $user,
                'pagination' => $followings
            )
        );
    }

}
