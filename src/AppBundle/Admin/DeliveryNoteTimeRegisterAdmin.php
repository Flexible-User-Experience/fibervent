<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class WorkOrderTaskAdmin.
 *
 * @category Admin
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class DeliveryNoteTimeRegisterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.deliverynotetimeregister.title';
    protected $baseRoutePattern = 'workorders/deliverynotetimeregister';
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
    );

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                )
            )
            ->add('type',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.type',
                )
            )
            ->add('shift',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                )
            )
            ->add('begin',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.begin',
                    'format' => 'h:m:s',
                )
            )
            ->add('end',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.end',
                    'format' => 'h:m:s',
                )
            )
            ->add('totalHours',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.total_hours',
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
            ->add(
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'id'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'deliveryNote')),
                )
            )
            ->add('type',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.type',
                    'template' => '::Admin/Cells/list__cell_delivery_note_time_register_type.html.twig',
                )
            )
            ->add('shift',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                    'template' => '::Admin/Cells/list__cell_delivery_note_time_register_shift.html.twig',
                )
            )
            ->add('begin',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.begin',
                    'format' => 'h:m:s',
                )
            )
            ->add('end',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.end',
                    'format' => 'h:m:s',
                )
            )
            ->add('totalHours',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.total_hours',
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'workOrder',
                null,
                array(
                    'label' => 'admin.workorder.title',
                )
            )
            ->add('description',
                null,
                array(
                    'label' => 'admin.workordertask.description',
                )
            )
            ->add('isCompleted',
                null,
                array(
                    'label' => 'admin.workordertask.is_completed',
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windmill',
                ModelType::class,
                array(
                    'label' => 'admin.windmill.title',
                    'btn_add' => false,
                    'required' => false,
                )
            )
            ->add('windmillBlade',
                ModelType::class,
                array(
                    'label' => 'admin.windmillblade.title',
                    'btn_add' => false,
                    'required' => false,
                )
            )
            ->end()
            ->with('admin.bladedamage.title', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'bladeDamage',
                ModelType::class,
                array(
                    'label' => 'admin.bladedamage.title',
                    'btn_add' => false,
                    'required' => true,
                    // 'query' => $this->bdr->findAll(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label' => 'admin.bladedamage.position',
                )
            )
            ->add(
                'radius',
                null,
                array(
                    'label' => 'admin.bladedamage.radius',
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label' => 'admin.bladedamage.distance',
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label' => 'admin.bladedamage.size',
                )
            )
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'workOrder',
                null,
                array(
                    'label' => 'admin.workorder.title',
                )
            )
            ->add('description',
                null,
                array(
                    'label' => 'admin.workordertask.description',
                )
            )
            ->add('isCompleted',
                null,
                array(
                    'label' => 'admin.workordertask.is_completed',
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windmill',
                ModelType::class,
                array(
                    'label' => 'admin.windmill.title',
                    'btn_add' => false,
                    'required' => false,
                )
            )
            ->add('windmillBlade',
                ModelType::class,
                array(
                    'label' => 'admin.windmillblade.title',
                    'btn_add' => false,
                    'required' => false,
                )
            )
            ->end()
            ->with('admin.bladedamage.title', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'bladeDamage',
                ModelType::class,
                array(
                    'label' => 'admin.bladedamage.title',
                    'btn_add' => false,
                    'required' => true,
                    // 'query' => $this->bdr->findAll(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label' => 'admin.bladedamage.position',
                )
            )
            ->add(
                'radius',
                null,
                array(
                    'label' => 'admin.bladedamage.radius',
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label' => 'admin.bladedamage.distance',
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label' => 'admin.bladedamage.size',
                )
            )
            ->end()
        ;
    }
}
