<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'text',
                TextareaType::class,
                array(
                    'label' => 'Mensaje',
                    'required' => true,
                    'attr' => array(
                        'class' => 'form-control'
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
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'document',
                FileType::class,
                array(
                    'label' => 'Documento',
                    'required' => false,
                    'data_class' => null,
                    'attr' => array(
                        'class' => 'form-control'
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
            'data_class' => 'AppBundle\Entity\Publication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'publication';
    }


}
