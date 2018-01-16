<?php

namespace AppBundle\Form\Type;

use AppBundle\Enum\AuditStatusEnum;
use AppBundle\Repository\AuditRepository;
use Doctrine\ORM\EntityManager;
use Sonata\CoreBundle\Form\Type\DateRangePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * WindfarmAuditStatsFormType class.
 *
 * @category FormType
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmAuditStatsFormType extends AbstractType
{
    const BLOCK_PREFIX = 'windfarm_audit_stats';

    /**
     * @var EntityManager
     */
    private $em;

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
     * @param EntityManager $em
     * @param AuditRepository $ar
     */
    public function __construct(EntityManager $em, AuditRepository $ar)
    {
        $this->em = $em;
        $this->ar = $ar;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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
                    'dates_range',
                    DateRangePickerType::class,
                    array(
                        'mapped' => false,
                        'required' => false,
                        'field_options' => array(
                            'format' => 'dd-MM-yyyy'
                        ),
                        'label' => 'admin.audit.dates_range',
                    )
                )
                ->add(
                    'generate',
                    SubmitType::class,
                    array(
                        'label' => 'admin.windfarm.select',
                        'attr' => array(
                            'class' => 'btn btn-success',
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
