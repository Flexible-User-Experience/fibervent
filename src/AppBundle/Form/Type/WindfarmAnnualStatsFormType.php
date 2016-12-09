<?php

namespace AppBundle\Form\Type;

use AppBundle\Repository\AuditRepository;
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
    const BLOCK_PREFIX = 'windfarm_annual_stat';

    /**
     * @var AuditRepository
     */
    private $ar;

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
        $yearsArray = array();
        $now = new \DateTime();
        $currentYear = intval($now->format('Y'));
        for ($i = 0; $i <= $currentYear - $this->ar->getFirstYearAudit(); $i++) {
            $result = $currentYear - $i;
            $yearsArray["$result"] = $result;
        }

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
