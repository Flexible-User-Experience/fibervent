<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * AuditEmailSendFormType class
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class AuditEmailSendFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'to',
                ChoiceType::class,
                array(
                    'mapped'   => false,
                    'required' => true,
                    'multiple' => false,
                    'label'    => 'Per a',
                    'choices'  => $options['mails'],
                )
            )
            ->add(
                'cc',
                ChoiceType::class,
                array(
                    'mapped'   => false,
                    'required' => false,
                    'multiple' => false,
                    'label'    => 'Amb còpia per a',
                    'choices'  => $options['mails'],
                )
            )
            ->add(
                'subject',
                TextType::class,
                array(
                    'mapped'   => false,
                    'required' => false,
                    'label'    => 'Assumpte',
                    'data'     => 'Resultado inspección Fibervent',
                )
            )
            ->add(
                'message',
                TextareaType::class,
                array(
                    'attr'     => array(
                        'rows' => '5',
                    ),
                    'mapped'   => false,
                    'required' => false,
                    'label'    => 'Missatge',
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'Enviar email',
                    'attr'  => array(
                        'class' => 'btn btn-success',
                    )
                )
            )
        ;
    }

    /**
     * Set oferta default form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Audit',
                'mails'      => null,
            )
        );
    }

    /**
     * Returns the blockname of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'audit_email_send';
    }
}
