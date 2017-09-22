<?php

namespace AppBundle\Form\Type;

use AppBundle\Enum\AuditStatusEnum;
use AppBundle\Repository\AuditRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * WindfarmAnnualStatsFormType class.
 *
 * @category FormType
 *
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
     * Methods.
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
        $yearsArray = $this->ar->getYearsOfAllAuditsByWindfarm($options['windfarm_id']);

        if (count($yearsArray) > 0) {
            $builder
                ->add(
                    'audit_status',
                    ChoiceType::class,
                    array(
                        'mapped' => false,
                        'required' => false,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'admin.audit.status',
                        'choices' => AuditStatusEnum::getReversedEnumArray(),
                        'choices_as_values' => true,
                        'data' => array(AuditStatusEnum::DONE, AuditStatusEnum::INVOICED),
                    )
                )
                ->add(
                    'year',
                    ChoiceType::class,
                    array(
                        'mapped' => false,
                        'required' => true,
                        'multiple' => false,
                        'label' => 'admin.audit.year',
                        'choices' => $yearsArray,
                    )
                )
                ->add(
                    'generate',
                    SubmitType::class,
                    array(
                        'label' => 'admin.audit.generate',
                        'attr' => array(
                            'class' => 'btn btn-success',
                        ),
                    )
                )
                ->add(
                    'download_xls',
                    SubmitType::class,
                    array(
                        'label' => 'admin.audit.download_xls',
                        'attr' => array(
                            'class' => 'btn btn-info',
                        ),
                    )
                )
            ;
        }
    }

    /**
     * Set default form options.
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
