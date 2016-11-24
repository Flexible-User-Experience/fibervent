<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * WindfarmAnnualStatsFormType class
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAnnualStatsFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'year',
                ChoiceType::class,
                array(
                    'mapped'   => false,
                    'required' => true,
                    'multiple' => false,
                    'label'    => 'Año',
//                    'choices'  => $options['to_emails_list'],
                    'choices'  => array(
                        '2017' => 2017,
                        '2016' => 2016,
                        '2015' => 2015
                    ),
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'Generar informe',
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
//                'default_msg'    => null,
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
        return 'windfar_annual_stat';
    }
}
