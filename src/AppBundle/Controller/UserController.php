<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute('home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'AppBundle::User/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error
            )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     * @Route("/logout", name="logout")
     */
    public function checkAction()
    {

    }

    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            return $this->redirectToRoute(
                'home'
            );
        }

        $user = new User();

        $form = $this->createForm(
            RegisterType::class,
            $user
        );

        $success = $this->get('register.form.handler')->process($form);

        if ($success == 1) {
                $this->addFlash(
                    'success',
                    'Cambios guardados con éxito'
                );

                return $this->redirectToRoute('login');
        } else {
            $this->addFlash(
                'error',
                'No se han podido guardar los cambios'
            );
        }

        return $this->render(
            'AppBundle::User/register.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/nick-test", name="user_nick_test")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function nickTestAction(Request $request)
    {
        $nick = $request->get('nick');

        $user = $this->get('user.service')->findOneByNick($nick);

        $result = 'unused';

        if ($user) {
            $result = 'used';
        }

        return new Response($result);
    }

    /**
     * @Route("/my-data", name="user_edit")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(
            UserType::class,
            $user
        );

        $success = $this->get('user.form.handler')->process($form);

        if ($success == 1) {
            $this->addFlash(
                'success',
                'Cambios guardados con éxito'
            );

            return $this->redirectToRoute('home');
        } else {
            $this->addFlash(
                'error',
                'No se han podido guardar los cambios'
            );
        }

        return $this->render(
            'AppBundle::User/edit.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/people", name="user_list")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function usersAction(Request $request)
    {
        $users = $this->get('user.service')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt(
                'page',
                1
            ),
            5
        );

        return $this->render(
            'AppBundle::User/users.html.twig',
            array(
                'pagination' => $pagination
            )
        );
    }

    /**
     * @Route("/search", name="user_search")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $search = trim($request->query->get('search', null));

        if (!$search) {
            return $this->redirectToRoute('home');
        }

        $users = $this->get('user.service')->findBySearch($search);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt(
                'page',
                1
            ),
            5
        );

        return $this->render(
            'AppBundle::User/users.html.twig',
            array(
                'pagination' => $pagination
            )
        );
    }

    /**
     * @Route("/user/{user}", name="user_profile")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param User|null $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function profileAction(User $user)
    {
        if (!$user) {
            $user = $this->getUser();
        }

        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home'));
        }

        $publications = $publications = $this->get('publication.service')
            ->findAllByUser(
                $user,
                false
            );

        return $this->render(
            'AppBundle:User:profile.html.twig',
            array(
                'user' => $user,
                'pagination' => $publications
            )
        );
    }
}
