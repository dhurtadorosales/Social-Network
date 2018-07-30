<?php

namespace AppBundle\Twig\Extension;


use Symfony\Bridge\Doctrine\RegistryInterface;

class FollowingExtension extends \Twig_Extension
{
    protected $doctrine;

    /**
     * FollowingExtension constructor.
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
                'following',
                array(
                    $this,
                    'followingFilter'
                )
            )
        );
    }

    /**
     * @param $following
     * @param $followed
     *
     * @return bool
     */
    public function followingFilter($following, $followed)
    {
        $manager = $this->doctrine->getRepository('AppBundle:Following');
        $userFollowing = $manager->findOneBy(
            array(
                'following' => $following,
                'followed' => $followed
            )
        );

        if (!empty($userFollowing) && is_object($userFollowing)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'following_extension';
    }
}