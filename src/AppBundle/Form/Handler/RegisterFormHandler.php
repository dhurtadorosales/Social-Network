<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterFormHandler
{
    private $request;
    private $userService;
    private $encoder;

    /**
     * RegisterFormHander constructor.
     *
     * @param RequestStack $requestStack
     * @param UserService $userService
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        RequestStack $requestStack,
        UserService $userService,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->userService = $userService;
        $this->encoder = $encoder;
    }

    /**
     * @param Form $form
     *
     * @return int
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(Form $form)
    {
        $form->handleRequest($this->request);

        $result = 0; //Not submitted

        if ($form->isSubmitted() && $this->request->isMethod('POST')) {
            $result = -1; //Not valid

            if ($form->isValid()) {
                $result = $this->onSuccess($form);
            }
        }

        return $result;
    }

    /**
     * @param Form $form
     *
     * @return int
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function onSuccess(Form $form)
    {
        $result = 1;

        /** @var User $user */
        $user = $form->getData();

        $password = $this->encoder->encodePassword(
            $user,
            $form->get('password')->getData()
        );

        $user
            ->setPassword($password)
            ->setRole('ROLE_USER')
            ->setImage(null)
            ->setActive(true);

        $this->userService->saveUser($user);

        return $result;

    }
}