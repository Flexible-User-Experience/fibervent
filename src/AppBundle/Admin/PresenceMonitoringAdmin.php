<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class WorkOrderTaskAdmin.
 *
 * @category Admin
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class PresenceMonitoringAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.presencemonitoring.title';
    protected $baseRoutePattern = 'presencemonitoring';
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
                'date',
                null,
                array(
                    'label' => 'admin.deliverynote.date',
                )
            )
            ->add('worker',
                null,
                array(
                    'label' => 'admin.workertimesheet.worker',
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
                'date',
                null,
                array(
                    'label' => 'admin.deliverynote.date',
                    'format' => 'd/M/Y',
                )
            )
            ->add('worker',
                null,
                array(
                    'label' => 'admin.presencemonitoring.worker',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'firstname'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'worker')),
                )
            )
            ->add('morningHourBegin',
                null,
                array(
                    'label' => 'admin.presencemonitoring.morning_hour_begin',
                    'format' => 'h:m:s',
                )
            )
            ->add('morningHourEnd',
                null,
                array(
                    'label' => 'admin.presencemonitoring.morning_hour_end',
                    'format' => 'h:m:s',
                )
            )
            ->add('afternoonHourBegin',
                null,
                array(
                    'label' => 'admin.presencemonitoring.afternoon_hour_begin',
                    'format' => 'h:m:s',
                )
            )
            ->add('afternoonHourEnd',
                null,
                array(
                    'label' => 'admin.presencemonitoring.afternoon_hour_end',
                    'format' => 'h:m:s',
                )
            )
            ->add('totalHours',
                null,
                array(
                    'label' => 'admin.presencemonitoring.total_hours',
                )
            )
            ->add('normalHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.normal_hours',
                )
            )
            ->add('extraHours',
                null,
                array(
                    'label' => 'admin.workertimesheet.extra_hours',
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
