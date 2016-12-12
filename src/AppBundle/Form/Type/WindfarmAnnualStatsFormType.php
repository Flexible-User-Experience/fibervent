<?php

namespace AppBundle\Form\Type;

use AppBundle\Repository\AuditRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * WindfarmAnnualStatsFormType class
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAnnualStatsFormType extends AbstractType
{
    const BLOCK_PREFIX = 'windfarm_annual_stat';

    /**
     * @var AuditRepository
     */
    private $ar;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * WindfarmAnnualStatsFormType constructor.
     *
     * @param AuditRepository $ar
     */
    public function __construct(AuditRepository $ar)
    {
        $this->ar = $ar;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearsArray = $this->ar->getYearsOfInvoicedOrDoneAuditsByWindfarm($options['windfarm_id']);

        if (count($yearsArray) > 0) {
            $builder
                ->add(
                    'year',
                    ChoiceType::class,
                    array(
                        'mapped'   => false,
                        'required' => true,
                        'multiple' => false,
                        'label'    => 'Año',
                        'choices'  => $yearsArray,
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
    }

    /**
     * Set default form options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'windfarm_id' => null,
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
        return self::BLOCK_PREFIX;
    }
}
