<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * UserProfileFormType class
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class UserProfileFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                array(
                    'label'     => 'Nom Usuari',
                    'required'  => false,
                    'read_only' => true,
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'     => 'Correu Electrònic',
                    'required'  => false,
                    'read_only' => true,
                )
            )
            ->add(
                'firstname',
                TextType::class,
                array(
                    'label'    => 'Nom',
                    'required' => true,
                )
            )
            ->add(
                'lastname',
                TextType::class,
                array(
                    'label'    => 'Cognom',
                    'required' => true,
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label'    => 'Telèfon',
                    'required' => false,
                )
            )
            ->add(
                'imageFile',
                FileType::class,
                array(
                    'label'    => ' ',
                    'required' => false,
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'label' => 'Actualitza',
                    'attr'  => array(
                        'class' => 'btn btn-success',
                    )
                )
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'user_profile';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
            )
        );
    }
}
