<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Windfarm;
use AppBundle\Enum\RepairAccessTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class WorkOrderAdmin.
 *
 * @category Admin
 */
class WorkOrderAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.workorder.title';
    protected $baseRoutePattern = 'workorders/workorder';
    protected $datagridValues = array(
        '_sort_by' => 'projectNumber',
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
            ->add('getWindfarmsFromCustomerId', $this->getRouterIdParameter().'/get-windfarms-from-customer-id')
            ->add('getWindmillbladesFromWindmillId', $this->getRouterIdParameter().'/get-windmillblades-from-windmill-id')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
                ->add('projectNumber',
                    null,
                    array(
                        'label' => 'admin.workorder.project_number',
                        'read_only' => true,
                    )
                )
                ->add(
                    'customer',
                    EntityType::class,
                    array(
                        'class' => Customer::class,
                        'label' => 'admin.windfarm.customer',
                        'read_only' => true,
                        'disabled' => true,
                    )
                )
                ->add('isFromAudit',
                    null,
                    array(
                        'label' => 'admin.workorder.is_from_audit',
                        'read_only' => true,
                        'disabled' => true,
                    )
                )
                ->end()
                ->with('admin.windfarm.title', $this->getFormMdSuccessBoxArray(4))
                ->add('windfarm',
                    EntityType::class,
                    array(
                        'class' => Windfarm::class,
                        'label' => 'admin.windfarm.title',
                        'read_only' => true,
                        'disabled' => true,
                    )
                )
                ->add(
                    'repairAccessTypes',
                    ChoiceType::class,
                    array(
                        'label' => 'admin.workorder.repair_access_types',
                        'choices' => RepairAccessTypeEnum::getEnumArray(),
                        'multiple' => true,
                        'expanded' => false,
                        'required' => true,
                    )
                )
                ->end()
                ->with('admin.workorder.certifying_company_name', $this->getFormMdSuccessBoxArray(4))
                ->add('certifyingCompanyName',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_name',
                    )
                )
                ->add('certifyingCompanyContactPerson',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_contact_person',
                    )
                )
                ->add('certifyingCompanyPhone',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_phone',
                    )
                )
                ->add('certifyingCompanyEmail',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_email',
                    )
                )
                ->end()
                ->with('admin.workordertask.title', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'workOrderTasks',
                    CollectionType::class,
                    array(
                        'label' => ' ',
                        'required' => false,
                        'btn_add' => true,
                        'cascade_validation' => true,
                        'error_bubbling' => true,
                        'type_options' => array(
                            'delete' => true,
                        ),
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
                ->end()
                ->with('admin.deliverynote.title', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'deliveryNotes',
                    CollectionType::class,
                    array(
                        'label' => ' ',
                        'required' => false,
                        'btn_add' => false,
                        'cascade_validation' => true,
                        'error_bubbling' => true,
                        'type_options' => array(
                            'delete' => false,
                        ),
                    ),
                    array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
                ->end()
            ;
            if ($this->getSubject()->isFromAudit()) {
                $formMapper
                    ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
                    ->add(
                        'audits',
                        ModelAutocompleteType::class,
                        array(
                            'label' => 'admin.audit.title',
                            'read_only' => 'true',
                            'required' => true,
                            'multiple' => true,
                            'btn_add' => false,
                            'property' => 'string',
                        )
                    )
                    ->end()
                    ;
            }
        } else { // is in create mode
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'customer',
                    ModelType::class,
                    array(
                        'label' => 'admin.windfarm.customer',
                        'required' => true,
                        'multiple' => false,
                        'btn_add' => false,
                        'query' => $this->cr->findEnabledSortedByNameQ(),
                        'choices_as_values' => true,
                    )
                )
                ->end()
                ->with('admin.windfarm.title', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'windfarm',
                    null,
                    array(
                        'label' => 'admin.windfarm.title',
                    )
                )
                ->add(
                    'repairAccessTypes',
                    ChoiceType::class,
                    array(
                        'label' => 'admin.workorder.repair_access_types',
                        'choices' => RepairAccessTypeEnum::getEnumArray(),
                        'multiple' => true,
                        'expanded' => false,
                        'required' => true,
                    )
                )
                ->end()
                ->with('admin.workorder.certifying_company_name', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'certifyingCompanyName',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_name',
                    )
                )
                ->add(
                    'certifyingCompanyContactPerson',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_contact_person',
                    )
                )
                ->add(
                    'certifyingCompanyPhone',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_phone',
                    )
                )
                ->add(
                    'certifyingCompanyEmail',
                    null,
                    array(
                        'label' => 'admin.workorder.certifying_company_email',
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
            ->add('projectNumber',
                null,
                array(
                    'label' => 'admin.workorder.project_number',
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                ),
                'entity',
                array(
                    'class' => Customer::class,
                    'query_builder' => $this->cr->findAllSortedByNameQB(),
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windfarm',
                null,
                array(
                    'label' => 'admin.windfarm.title',
                ),
                'entity',
                array(
                    'class' => Windfarm::class,
                    'query_builder' => $this->wfr->findAllSortedByNameQB(),
                )
            )
            ->add('audits',
                null,
                array(
                    'label' => 'admin.audit.title',
                ),
                'entity',
                array(
                    'class' => Audit::class,
                    'query_builder' => $this->ar->getAllAuditsJoinedSortedByBeginDateQB(),
                    'choice_label' => 'toStringWithoutJoins',
                )
            )
            ->add('certifyingCompanyName',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_name',
                )
            )
            ->add('certifyingCompanyContactPerson',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_contact_person',
                )
            )
            ->add('certifyingCompanyPhone',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_phone',
                )
            )
            ->add('certifyingCompanyEmail',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_email',
                )
            )
            ->add('repairAccessTypes',
                null,
                array(
                    'label' => 'admin.workorder.repair_access_types',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add('projectNumber',
                null,
                array(
                    'label' => 'admin.workorder.project_number',
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'customer')),
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windfarm',
                null,
                array(
                    'label' => 'admin.windfarm.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windfarm')),
                )
            )
            ->add('audits',
                null,
                array(
                    'label' => 'admin.audit.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'audits')),
                )
            )
            ->add('certifyingCompanyName',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_name',
                )
            )
            ->add('certifyingCompanyContactPerson',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_contact_person',
                )
            )
            ->add('certifyingCompanyPhone',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_phone',
                )
            )
            ->add('certifyingCompanyEmail',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_email',
                )
            )
            ->add('repairAccessTypes',
                null,
                array(
                    'label' => 'admin.workorder.repair_access_types',
                    'template' => '::Admin/Cells/list__cell_repair_access_type.html.twig',
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
                    ),
                )
            )
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add('projectNumber',
                null,
                array(
                    'label' => 'admin.workorder.project_number',
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('audits',
                null,
                array(
                    'label' => 'admin.audit.title',
                )
            )
            ->end()
            ->with('admin.windfarm.title', $this->getFormMdSuccessBoxArray(4))
            ->add('windfarm',
                null,
                array(
                    'label' => 'admin.windfarm.title',
                )
            )
            ->add(
                'repairAccessTypes',
                null,
                array(
                    'label' => 'admin.workorder.repair_access_types',
                    'template' => '::Admin/Cells/list__repair_access_type.html.twig',
                )
            )
            ->end()
            ->with('admin.workorder.certifying_company_name', $this->getFormMdSuccessBoxArray(4))
            ->add('certifyingCompanyName',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_name',
                )
            )
            ->add('certifyingCompanyContactPerson',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_contact_person',
                )
            )
            ->add('certifyingCompanyPhone',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_phone',
                )
            )
            ->add('certifyingCompanyEmail',
                null,
                array(
                    'label' => 'admin.workorder.certifying_company_email',
                )
            )
            ->end()
            ->with('admin.workordertask.title', $this->getFormMdSuccessBoxArray(12))
            ->add(
                'workOrderTasks',
                null,
                array(
                    'label' => 'admin.workordertasks.title',
                    'template' => '::Admin/Cells/list__work_order_tasks.html.twig',
                )
            )
            ->end()
            ->with('admin.deliverynote.title', $this->getFormMdSuccessBoxArray(12))
            ->add(
                'deliveryNotes',
                null,
                array(
                    'label' => 'admin.workordertasks.title',
                    'template' => '::Admin/Cells/list__delivery_notes.html.twig',
                )
            )
            ->end()
        ;
    }
}
