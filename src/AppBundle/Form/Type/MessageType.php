<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'receiver',
                EntityType::class,
                array(
                    'class' => 'AppBundle\Entity\User',
                    'query_builder' => function(UserRepository $userRepository) use ($options) {
                        return $userRepository->findFollowingUsers($options['empty_data']);
                    },
                    'choice_label' => function(User $user) {
                        return $user->getFirstName() . ' ' . $user->getLastName() . ' - ' . $user->getNick();
                    },
                    'label' => 'Para:',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'message',
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
                    'label' => 'Imagen',
                    'required' => false,
                    'data_class' => null,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'file',
                FileType::class,
                array(
                    'label' => 'Archivo',
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
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'message';
    }


}
