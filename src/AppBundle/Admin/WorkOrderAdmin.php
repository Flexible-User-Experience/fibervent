<?php

namespace AppBundle\Admin;

use AppBundle\Enum\RepairAccessTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
            ->remove('batch');
//            ->add('pdf', $this->getRouterIdParameter().'/pdf')
//            ->add('email', $this->getRouterIdParameter().'/email');
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
                )
            )
            ->add('audit',
                null,
                array(
                    'label' => 'admin.audit.title',
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
            ->add('audit',
                null,
                array(
                    'label' => 'admin.audit.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'audit')),
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
 //                       'excel' => array('template' => '::Admin/Buttons/list__action_excel_button.html.twig'),
 //                       'pdf' => array('template' => '::Admin/Buttons/list__action_pdf_windfarm_button.html.twig'),
                    ),
                )
            )
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
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
            ->add('audit',
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
    }

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
            ->add('audit',
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
