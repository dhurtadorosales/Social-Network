<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                array(
                    'label' => 'Nombre',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-first-name form-control'
                    )
                )
            )
            ->add(
                'lastName',
                TextType::class,
                array(
                    'label' => 'Apellidos',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-last-name form-control'
                    )
                )
            )
            ->add(
                'nick',
                TextType::class,
                array(
                    'label' => 'Nick',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-nick form-control nick-input'
                    )
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'Email',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-email form-control'
                    )
                )
            )
            ->add(
                'bio',
                TextareaType::class,
                array(
                    'label' => 'Biografía',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-bio form-control'
                    )
                )
            )
            ->add(
                'image',
                FileType::class,
                array(
                    'label' => 'Foto',
                    'required' => false,
                    'data_class' => null,
                    'attr' => array(
                        'class' => 'form-image form-control'
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user';
    }


}
