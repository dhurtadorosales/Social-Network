<?php

namespace AppBundle\Twig\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension
{
    protected $doctrine;

    /**
     * UserStatsExtension constructor.
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
                'user_stats',
                array(
                    $this,
                    'userStatsFilter'
                )
            )
        );
    }

    /**
     * @param $user
     *
     * @return array
     */
    public function userStatsFilter($user)
    {
        $followingManager = $this->doctrine->getRepository('AppBundle:Following');
        $publicationManager = $this->doctrine->getRepository('AppBundle:Publication');
        $likeManager = $this->doctrine->getRepository('AppBundle:Like');

        $userFollowing = $followingManager->findBy(
            array(
                'following' => $user
            )
        );

        $userFollowers = $followingManager->findBy(
            array(
                'followed' => $user
            )
        );

        $userPublications = $publicationManager->findBy(
            array(
                'user' => $user
            )
        );

        $userLikes = $likeManager->findBy(
            array(
                'user' => $user
            )
        );

        $result = array(
            'following' => count($userFollowing),
            'followers' => count($userFollowers),
            'publications' => count($userPublications),
            'likes' => count($userLikes),
        );

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_stats';
    }
}
