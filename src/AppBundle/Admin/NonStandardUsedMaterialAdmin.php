<?php

namespace AppBundle\Admin;

use AppBundle\Enum\TimeRegisterShiftEnum;
use AppBundle\Enum\TimeRegisterTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

/**
 * Class WorkOrderTaskAdmin.
 *
 * @category Admin
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class NonStandardUsedMaterialAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.nonstandardusedmaterial.title';
    protected $baseRoutePattern = 'workorders/nonstandardusedmaterial';
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
            ->add('item',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.item',
                )
            )
            ->add('quantity',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.quantity',
                )
            )
            ->add('description',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.description',
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
            ->add('item',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.item',
                    'template' => '::Admin/Cells/list__cell_non_standard_used_material_item.html.twig',
                )
            )
            ->add('quantity',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.quantity',
                )
            )
            ->add('description',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.description',
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                )
            )
            ->add('type',
                ChoiceType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.type',
                    'choices' => TimeRegisterTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                )
            )
            ->add('shift',
                ChoiceType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                    'choices' => TimeRegisterShiftEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                )
            )
            ->add('begin',
                TimeType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.begin',
                )
            )
            ->add('end',
                TimeType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.end',
                )
            )
            ->add('totalHours',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.total_hours',
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
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
                    'template' => '::Admin/Cells/list__delivery_note_time_register_type.html.twig',
                )
            )
            ->add('shift',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                    'template' => '::Admin/Cells/list__delivery_note_time_register_shift.html.twig',
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
            ->end()
        ;
    }
}
