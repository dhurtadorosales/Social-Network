<?php

namespace AppBundle\Twig\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;

class LikedExtension extends \Twig_Extension
{
    protected $doctrine;

    /**
     * LikedExtension constructor.
     *
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return array|\Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'liked',
                array(
                    $this,
                    'likedFilter'
                )
            )
        );
    }

    /**
     * @param $user
     * @param $publication
     *
     * @return bool
     */
    public function likedFilter($user, $publication)
    {
        $manager = $this->doctrine->getRepository('AppBundle:Like');
        $publicationLiked = $manager->findOneBy(
            array(
                'user' => $user,
                'publication' => $publication
            )
        );

        if (!empty($publicationLiked) && is_object($publicationLiked)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'liked';
    }
}
