<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Like;
use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use AppBundle\Service\LikeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LikeController extends Controller
{
    /**
     * @var LikeService $likeService
     */
    private $likeService;

    /**
     * LikeController constructor.
     *
     * @param LikeService $likeService
     */
    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * @Route("/like/{id}", name="like_publication")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Publication|null $publication
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function likeAction(Publication $publication = null)
    {
        $user = $this->getUser();

        $like = new Like();
        $like
            ->setUser($user)
            ->setPublication($publication);

        $result = 400;

        if ($this->likeService->saveLike($like)) {
            $result = 200;

            //Send notification
            $this->get('notification.service')
                ->setNotification(
                    $publication->getUser(),
                    'like',
                    $user->getId(),
                    $publication->getId()
                );
        }

        return new Response($result);
    }

    /**
     * @Route("/unlike/{id}", name="unlike_publication")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Publication|null $publication
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function unLikeAction(Publication $publication = null)
    {
        $result = 200;

        $like = $this->likeService->findByPublication($publication);

        if (!$this->likeService->removeLike($like)) {
            $result = 400;
        };

        return new Response($result);
    }

    /**
     * @Route("/likes/{user}", name="user_likes")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function likesAction(User $user)
    {
        if (!$user) {
            $user = $this->getUser();
        }

        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home'));
        }

        $likes = $this->get('like.service')
            ->findAllByUser($user);

        return $this->render(
            'AppBundle:Like:likes.html.twig',
            array(
                'user' => $user,
                'pagination' => $likes
            )
        );
    }
}
