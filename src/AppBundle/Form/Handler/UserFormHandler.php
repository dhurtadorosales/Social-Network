<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFormHandler
{
    private $request;
    private $userService;

    /**
     * RegisterFormHander constructor.
     *
     * @param RequestStack $requestStack
     * @param UserService $userService
     */
    public function __construct(
        RequestStack $requestStack,
        UserService $userService
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->userService = $userService;
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

        $userImage = $user->getImage();
        $file = $form['image']->getData();

        $user->setImage($userImage);
        if (!empty($file) && $file != null ) {
            $ext = $file->guessExtension();
            if  (
                $ext == 'jpg' ||
                $ext == 'jpeg' ||
                $ext == 'png' ||
                $ext == 'gif'
            ) {
                $fileName = $user->getId() . time() . '.' . $ext;
                $file->move('uploads/users', $fileName);

                $user->setImage($fileName);
            }
        }

        $this->userService->saveUser($user);

        return $result;

    }
}