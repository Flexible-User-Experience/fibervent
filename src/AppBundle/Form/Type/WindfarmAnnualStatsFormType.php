<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\DamageCategory;
use AppBundle\Enum\AuditStatusEnum;
use AppBundle\Repository\AuditRepository;
use AppBundle\Repository\DamageCategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    const BLOCK_PREFIX = 'windfarm_annual_stats';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var AuditRepository
     */
    private $ar;

    /**
     * @var DamageCategoryRepository
     */
    private $dcr;

    /**
     * Methods.
     */

    /**
     * WindfarmAnnualStatsFormType constructor.
     *
     * @param EntityManager            $em
     * @param AuditRepository          $ar
     * @param DamageCategoryRepository $dcr
     */
    public function __construct(EntityManager $em, AuditRepository $ar, DamageCategoryRepository $dcr)
    {
        $this->em = $em;
        $this->ar = $ar;
        $this->dcr = $dcr;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws ORMException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearsArray = $this->ar->getYearsOfAllAuditsByWindfarm($options['windfarm_id']);

        if (count($yearsArray) > 0) {
            $defaultDamageCategoryData = array();
            $damageCategories = $this->dcr->findAllSortedByCategory();
            /** @var DamageCategory $damageCategory */
            foreach ($damageCategories as $damageCategory) {
                $defaultDamageCategoryData[] = $this->em->getReference('AppBundle:DamageCategory', $damageCategory->getId());
            }

            $builder
                ->add(
                    'damage_categories',
                    EntityType::class,
                    array(
                        'mapped' => false,
                        'required' => false,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => 'admin.bladedamage.damagecategory_long',
                        'class' => 'AppBundle\Entity\DamageCategory',
                        'query_builder' => $this->dcr->findAllSortedByCategoryQB(),
                        'data' => $defaultDamageCategoryData,
                    )
                )
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
