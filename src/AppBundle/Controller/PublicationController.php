<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Publication;
use AppBundle\Form\Type\PublicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PublicationController extends Controller
{
    /**
     * @Route("/", name="home")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction()
    {
        $publication = new Publication();

        $form = $this->createForm(
            PublicationType::class,
            $publication
        );

        if ($this->getUser()) {
            $success = $this->get('publication.form.handler')->process($form, $this->getUser());

            if ($success == 1) {
                $this->addFlash(
                    'success',
                    'Publicación creada'
                );

                return $this->redirectToRoute('home');

            } else {
                $this->addFlash(
                    'error',
                    'No se ha podido crear la publicación'
                );
            }
        }

        $publications = $this->get('publication.service')->findAllByUser($this->getUser(), true);

        return $this->render(
            'AppBundle:Publication:home.html.twig',
            array(
                'form' => $form->createView(),
                'pagination' => $publications
            )
        );
    }

    /**
     * @Route("/publication/remove/{publication}", name="remove_publication")
     *
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Publication|null $publication
     *
     * @param Publication|null $publication
     */
    public function removePublicationAction(Request $request, Publication $publication = null)
    {
        $status = 'No eres el dueño';
        if ($this->getUser()->getId() == $publication->getUser()->getId()) {
            $status = 'No se ha podido eliminar la publicación';
            if ($this->get('publication.service')->removePublication($publication)) {

                $status = 'Publicación eliminada correctamente';
            }
        }

        return $this->addFlash(
            'error',
            'No se ha podido crear la publicación'
        );
    }
}
