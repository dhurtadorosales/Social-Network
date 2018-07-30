<?php

namespace AppBundle\Twig\Extension;

class ClassNameExtension extends \Twig_Extension
{
    /**
     * @return array|\Twig_SimpleFilter[]
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'class_name',
                array(
                    $this,
                    'classNameFilter'
                )
            )
        );
    }

    /**
     * @param $object
     * @return string
     *
     * @throws \ReflectionException
     */
    public function classNameFilter($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'class_name';
    }
}
