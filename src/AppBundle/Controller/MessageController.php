<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\Type\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MessageController extends Controller
{
    /**
     * @Route("/messages", name="messages")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function messagesAction()
    {
        $message = new Message();

        $form = $this->createForm(
            MessageType::class,
            $message,
            array(
                'empty_data' => $this->getUser()
            )
        );

        $success = $this->get('message.form.handler')->process($form, $this->getUser());

        if ($success == 1) {
            $this->addFlash(
                'success',
                'Mensaje enviado'
            );

            return $this->redirectToRoute('messages');

        } else {
            $this->addFlash(
                'error',
                'No se ha podido enviar el mensaje'
            );
        }

        $receivedMessages = $this->get('message.service')
            ->findMessages($this->getUser(), false);

        //Set readed messages
        $this->get('message.service')->setReaded($this->getUser());

        return $this->render(
            'AppBundle::Message/messages.html.twig',
            array(
                'form' => $form->createView(),
                'pagination' => $receivedMessages
            )
        );
    }

    /**
     * @Route("/messages/sended", name="message_sended")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendedAction()
    {
        $messages = $this->get('message.service')
            ->findMessages($this->getUser(), true);

        return $this->render(
            'AppBundle::Message/sended.html.twig',
            array(
                'pagination' => $messages
            )
        );
    }

    /**
     * @Route("/messages/count", name="message_count")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countNotReadedMessagesAction()
    {
        $nbMessages = $this->get('message.service')
            ->countNotReadedByUser($this->getUser());

        return new Response($nbMessages);
    }
}
