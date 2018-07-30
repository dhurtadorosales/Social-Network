<?php

namespace AppBundle\Twig\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;

class GetUserExtension extends \Twig_Extension
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
                'get_user',
                array(
                    $this,
                    'getUserFilter'
                )
            )
        );
    }

    /**
     * @param $userId
     *
     * @return bool
     */
    public function getUserFilter($userId)
    {
        $manager = $this->doctrine->getRepository('AppBundle:User');
        $user = $manager->findOneBy(
            array(
                'id' => $userId
            )
        );

        $result = false;

        if (!empty($user) && is_object($user)) {
            $result = $user;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'get_user';
    }
}