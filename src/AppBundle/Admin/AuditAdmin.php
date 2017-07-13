<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Entity\BladePhoto;
use AppBundle\Entity\User;
use AppBundle\Enum\AuditDiagramTypeEnum;
use AppBundle\Enum\AuditStatusEnum;
use AppBundle\Enum\AuditTypeEnum;
use AppBundle\Enum\UserRolesEnum;
use AppBundle\Form\Type\AuditDiagramTypeFormType;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class AuditAdmin.
 *
 * @category Admin
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.audit.title';
    protected $baseRoutePattern = 'audits/audit';
    protected $datagridValues = array(
        '_sort_by' => 'beginDate',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('batch')
            ->add('pdf', $this->getRouterIdParameter().'/pdf')
            ->add('email', $this->getRouterIdParameter().'/email');
    }

    /**
     * Override query list to reduce queries amount on list view (apply join strategy).
     *
     * @param string $context context
     *
     * @return QueryBuilder
     */
    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        $query
            ->select($query->getRootAliases()[0].', wm, wf, c, u')
            ->join($query->getRootAliases()[0].'.windmill', 'wm')
            ->join('wm.windfarm', 'wf')
            ->join('wf.customer', 'c')
            ->leftJoin($query->getRootAliases()[0].'.operators', 'u');
        // Customer filter
        if ($this->acs->isGranted(UserRolesEnum::ROLE_CUSTOMER)) {
            /** @var User $user */
            $user = $this->tss->getToken()->getUser();
            $query
                ->andWhere($query->getRootAliases()[0].'.customer = :customer')
                ->setParameter('customer', $user->getCustomer())
            ;
        }

        return $query;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'windmill',
                'sonata_type_model',
                array(
                    'label' => 'admin.audit.windmill',
                    'btn_add' => false,
                    'btn_delete' => false,
                    'required' => true,
                    'query' => $this->wmr->findEnabledSortedByCustomerWindfarmAndWindmillCodeQ(),
                )
            )
            ->add(
                'observations',
                TextareaType::class,
                array(
                    'label' => 'admin.audit.observations',
                    'required' => false,
                    'attr' => array(
                        'rows' => 10,
                    ),
                )
            )
            ->end()
            ->with('admin.common.controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'beginDate',
                'sonata_type_date_picker',
                array(
                    'label' => 'admin.audit.begindate',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'endDate',
                'sonata_type_date_picker',
                array(
                    'label' => 'admin.audit.enddate',
                    'format' => 'd/M/y',
                    'required' => false,
                )
            )
            ->add(
                'operators',
                'sonata_type_model',
                array(
                    'label' => 'admin.audit.operators',
                    'multiple' => true,
                    'required' => false,
                    'btn_add' => false,
                    'btn_delete' => false,
                    'property' => 'contactInfoString',
                    'query' => $this->ur->findAllTechniciansSortedByNameQ(),
                )
            )
            ->add(
                'type',
                ChoiceType::class,
                array(
                    'label' => 'admin.audit.type',
                    'choices' => AuditTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'status',
                ChoiceType::class,
                array(
                    'label' => 'admin.audit.status',
                    'choices' => AuditStatusEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->end()
            ->with('admin.audit.diagramtype_title', $this->getFormMdSuccessBoxArray(8))
            ->add(
                'diagramType',
                AuditDiagramTypeFormType::class,
                array(
                    'label' => 'admin.audit.diagramtype',
                    'choices' => AuditDiagramTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('admin.audit.auditwindmillblade_title', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'auditWindmillBlades',
                    'sonata_type_collection',
                    array(
                        'label' => ' ',
                        'required' => false,
                        'btn_add' => false,
                        'cascade_validation' => true,
                        'type_options' => array(
                            'delete' => false,
                        ),
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
                ->end();
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'beginDate',
                'doctrine_orm_date',
                array(
                    'label' => 'admin.audit.begindate',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'endDate',
                'doctrine_orm_date',
                array(
                    'label' => 'admin.audit.enddate',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'windmill.windfarm.customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                )
            )
            ->add(
                'windmill.windfarm',
                null,
                array(
                    'label' => 'admin.windmill.windfarm',
                )
            )
            ->add(
                'windmill',
                null,
                array(
                    'label' => 'admin.audit.windmill',
                )
            )
            ->add(
                'operators',
                null,
                array(
                    'label' => 'admin.audit.operators',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label' => 'admin.audit.status',
                ),
                'choice',
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => AuditStatusEnum::getEnumArray(),
                )
            )
            ->add(
                'diagramType',
                null,
                array(
                    'label' => 'admin.audit.diagramtype',
                ),
                'choice',
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => AuditDiagramTypeEnum::getEnumArray(),
                )
            )
            ->add(
                'year',
                'doctrine_orm_callback',
                array(
                    'label' => 'admin.audit.year',
                    'show_filter' => true,
                    'callback' => function ($queryBuilder, $alias, $field, $value) {
                        if (!$value['value']) {
                            $currentYear = new \DateTime();
                            $value['value'] = intval($currentYear->format('Y'));
                        }

                        /* @var QueryBuilder $queryBuilder */
                        $queryBuilder->andWhere('YEAR('.$alias.'.beginDate) = :year');
                        $queryBuilder->setParameter('year', $value['value']);

                        return true;
                    },
                ),
                'choice',
                array(
                    'choices' => $this->ar->getYearChoices(),
                    'required' => true,
                )
            )
            ->add(
                'observations',
                null,
                array(
                    'label' => 'admin.audit.observations',
                )
            );
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'beginDate',
                null,
                array(
                    'label' => 'admin.audit.begindate',
                    'format' => 'd/m/Y',
                )
            )
            ->add(
                'windmill.windfarm.customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'customer')),
                )
            )
            ->add(
                'windmill.windfarm',
                null,
                array(
                    'label' => 'admin.windmill.windfarm',
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windfarm')),
                )
            )
            ->add(
                'windmill',
                null,
                array(
                    'label' => 'admin.audit.windmill',
                    'associated_property' => 'code',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'code'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windmill')),
                )
            )
            ->add(
                'operators',
                null,
                array(
                    'label' => 'admin.audit.operators',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label' => 'admin.audit.status',
                    'template' => '::Admin/Cells/list__cell_audit_status.html.twig',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'admin.common.action',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'show' => array('template' => '::Admin/Buttons/list__action_show_button.html.twig'),
                        'pdf' => array('template' => '::Admin/Buttons/list__action_pdf_button.html.twig'),
                        'email' => array('template' => '::Admin/Buttons/list__action_email_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                )
            );
    }

    /**
     * @param Audit $object
     */
    public function prePersist($object)
    {
        // create the related three auditwindmillblade entities
        $windmillBlades = $object->getWindmill()->getWindmillBlades();

        $auditWindmillBlade1 = new AuditWindmillBlade();
        $auditWindmillBlade1
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[0]);
        $auditWindmillBlade2 = new AuditWindmillBlade();
        $auditWindmillBlade2
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[1]);
        $auditWindmillBlade3 = new AuditWindmillBlade();
        $auditWindmillBlade3
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[2]);

        // create 4 empty photos slots per blade
        $auditWindmillBlade1
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
        ;
        $auditWindmillBlade2
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
        ;
        $auditWindmillBlade3
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
            ->addBladePhoto(new BladePhoto())
        ;

        $object
            ->addAuditWindmillBlade($auditWindmillBlade1)
            ->addAuditWindmillBlade($auditWindmillBlade2)
            ->addAuditWindmillBlade($auditWindmillBlade3);

        $this->commomPreEvent($object);
    }

    /**
     * @param Audit $object
     */
    public function preUpdate($object)
    {
        //// update the related three auditwindmillblade entities
        $this->commomPreEvent($object);

        // fetch new windmill blades
        $newWindmillBlades = $object->getWindmill()->getWindmillBlades();
        // replace old relations
        $currentAuditWindmillBlades = $object->getAuditWindmillBlades();
        $currentAuditWindmillBlades[0]
            ->setAudit($object)
            ->setWindmillBlade($newWindmillBlades[0]);
        $currentAuditWindmillBlades[1]
            ->setAudit($object)
            ->setWindmillBlade($newWindmillBlades[1]);
        $currentAuditWindmillBlades[2]
            ->setAudit($object)
            ->setWindmillBlade($newWindmillBlades[2]);
    }

    /**
     * @param Audit $object
     */
    private function commomPreEvent($object)
    {
        // set audit relations
        $object
            ->setWindfarm($object->getWindmill()->getWindfarm())
            ->setCustomer($object->getWindmill()->getWindfarm()->getCustomer());
    }
}
