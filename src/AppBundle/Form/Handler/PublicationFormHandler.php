<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\Publication;
use AppBundle\Entity\User;
use AppBundle\Service\PublicationService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;

class PublicationFormHandler
{
    private $request;
    private $publicationService;

    /**
     * PublicationFormHandler constructor.
     *
     * @param RequestStack $requestStack
     * @param PublicationService $publicationService
     */
    public function __construct(
        RequestStack $requestStack,
        PublicationService $publicationService
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->publicationService = $publicationService;
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

        /** @var Publication $publication */
        $publication = $form->getData();

        //Upload image
        $file = $form->get('image')->getData();

        $publication->setImage(null);
        if (!empty($file) && $file != null) {
            $ext = $file->guessExtension();
            $publication->setImage(null);

            if (
                $ext == 'jpg' ||
                $ext == 'jpeg' ||
                $ext == 'png' ||
                $ext == 'gif'
            ) {
                $fileName =  $user->getId() . time() . '.' . $ext;
                $file->move('uploads/publications/images', $fileName);

                $publication->setImage($fileName);
            }
        }

        //Upload document
        $doc = $form->get('document')->getData();

        $publication->setDocument(null);
        if (!empty($doc) && $doc != null) {
            $ext = $doc->guessExtension();
            $publication->setDocument(null);

            if (
                $ext == 'jpg' ||
                $ext == 'jpeg' ||
                $ext == 'png' ||
                $ext == 'gif'
            ) {
                $docName =  $user->getId() . time() . '.' . $ext;
                $doc->move('uploads/publications/documents', $docName);

                $publication->setDocument($docName);
            }
        }

        $publication
            ->setUser($user)
            ->setCreatedAt(new \DateTime('now'));

        $this->publicationService->savePublication($publication);

        return $result;

    }
}