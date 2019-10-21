<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class WorkOrderTaskAdmin.
 *
 * @category Admin
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class WorkerTimesheetAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.workertimesheet.title';
    protected $baseRoutePattern = 'workorders/workertimesheet';
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
    );

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
            ->add('worker',
                null,
                array(
                    'label' => 'admin.workertimesheet.worker',
                )
            )
            ->add('workDescription',
                null,
                array(
                    'label' => 'admin.workertimesheet.work_description',
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
            ->add('worker',
                null,
                array(
                    'label' => 'admin.workertimesheet.worker',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'firstname'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'worker')),
                )
            )
            ->add('workDescription',
                null,
                array(
                    'label' => 'admin.workertimesheet.work_description',
                )
            )
            ->add('totalNormalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_normal_hours',
                )
            )
            ->add('totalVerticalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_vertical_hours',
                )
            )
            ->add('totalInclementWeatherHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_inclement_weather_hours',
                )
            )
            ->add('totalTripHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_trip_hours',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'admin.common.action',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
//                        'show' => array('template' => '::Admin/Buttons/list__action_show_button.html.twig'),
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
            ->add(
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                )
            )
            ->add('worker',
                null,
                array(
                    'label' => 'admin.workertimesheet.worker',
                )
            )
            ->add('workDescription',
                null,
                array(
                    'label' => 'admin.workertimesheet.work_description',
                )
            )
            ->end()
            ->with('admin.common.details', $this->getFormMdSuccessBoxArray(4))
            ->add('totalNormalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_normal_hours',
                )
            )
            ->add('totalVerticalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_vertical_hours',
                )
            )
            ->add('totalInclementWeatherHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_inclement_weather_hours',
                )
            )
            ->add('totalTripHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_trip_hours',
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                )
            )
            ->add('worker',
                null,
                array(
                    'label' => 'admin.workertimesheet.worker',
                )
            )
            ->add('workDescription',
                null,
                array(
                    'label' => 'admin.workertimesheet.work_description',
                )
            )
            ->end()
            ->with('admin.common.details', $this->getFormMdSuccessBoxArray(4))
            ->add('totalNormalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_normal_hours',
                )
            )
            ->add('totalVerticalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_vertical_hours',
                )
            )
            ->add('totalInclementWeatherHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_inclement_weather_hours',
                )
            )
            ->add('totalTripHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.total_trip_hours',
                )
            )
            ->end()
        ;
    }
}
