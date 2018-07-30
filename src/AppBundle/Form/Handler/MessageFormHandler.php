<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Service\MessageService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;

class MessageFormHandler
{
    private $request;
    private $messageService;

    /**
     * Message FormHandler constructor.
     *
     * @param RequestStack $requestStack
     * @param MessageService $messageService
     */
    public function __construct(
        RequestStack $requestStack,
        MessageService $messageService
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->messageService = $messageService;
    }

    /**
     * @param Form $form
     * @param User $user
     *
     * @return int
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(Form $form, User $user)
    {
        $form->handleRequest($this->request);

        $result = 0; //Not submitted

        if ($form->isSubmitted() && $this->request->isMethod('POST')) {
            $result = -1; //Not valid

            if ($form->isValid()) {
                $result = $this->onSuccess($form, $user);
            }
        }

        return $result;
    }

    /**
     * @param Form $form
     * @param User $user
     *
     * @return int
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function onSuccess(Form $form, User $user)
    {
        $result = 1;

        /** @var Message $message */
        $message = $form->getData();

        //Upload image
        $file = $form->get('image')->getData();

        $message->setImage(null);
        if (!empty($file) && $file != null) {
            $ext = $file->guessExtension();
            $message->setImage(null);

            if (
                $ext == 'jpg' ||
                $ext == 'jpeg' ||
                $ext == 'png' ||
                $ext == 'gif'
            ) {
                $fileName =  $user->getId() . time() . '.' . $ext;
                $file->move('uploads/messages/images', $fileName);

                $message->setImage($fileName);
            }
        }

        //Upload file
        $doc = $form->get('file')->getData();

        $message->setFile(null);
        if (!empty($doc) && $doc != null) {
            $ext = $doc->guessExtension();
            $message->setFile(null);

            if (
                $ext == 'jpg' ||
                $ext == 'jpeg' ||
                $ext == 'png' ||
                $ext == 'gif'
            ) {
                $docName =  $user->getId() . time() . '.' . $ext;
                $doc->move('uploads/messages/files', $docName);

                $message->setFile($docName);
            }
        }

        $message
            ->setSender($user)
            ->setCreatedAt(new \DateTime('now'))
            ->setReaded(false);

        $this->messageService->saveMessage($message);

        return $result;

    }
}