<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Windfarm;
use AppBundle\Entity\WorkOrder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;

/**
 * Class WorkOrderTaskAdmin.
 *
 * @category Admin
 *
 * @author   Jordi Sort <jordi.sort@mirmit.com>
 */
class WorkOrderTaskAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.workordertask.title';
    protected $baseRoutePattern = 'workorders/workordertask';
    protected $datagridValues = array(
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->getRootCode() == $this->getCode()) {
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'workOrder',
                    null,
                    array(
                        'label' => 'admin.workorder.title',
                    )
                )
                ->end()
            ;
        } else {
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'workOrder',
                    null,
                    array(
                        'label' => 'admin.workorder.title',
                        'attr' => array(
                            'hidden' => true,
                        ),
                    )
                )
                ->end()
            ;
            /** @var WorkOrder $workOrder */
            $workOrder = $this->getRoot()->getSubject();
            /** @var Windfarm $windfarm */
            $windfarm = $workOrder->getWindfarm();
        }
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            if ($this->getSubject()->isFromAudit()) {
                $formMapper
                    ->with('admin.bladedamage.title', $this->getFormMdSuccessBoxArray(5))
                    ->add('windmill',
                        ModelAutocompleteType::class,
                        array(
                            'label' => 'admin.windmill.title',
                            'btn_add' => false,
                            'required' => false,
                            'property' => 'code',
                            'read_only' => true,
                        )
                    )
                    ->add('windmillBlade',
                        ModelAutocompleteType::class,
                        array(
                            'label' => 'admin.windmillblade.title',
                            'btn_add' => false,
                            'required' => false,
                            'property' => 'code',
                            'read_only' => true,
                        )
                    )
                    ->add(
                        'bladeDamage',
                        ModelAutocompleteType::class,
                        array(
                            'label' => 'admin.bladedamage.title',
                            'read_only' => true,
                            'disabled' => true,
                            //                    'btn_add' => false,
                            //                    'required' => true,
                            //                    // 'query' => $this->bdr->findAll(),
                            'property' => 'damage.code',
                        )
                    )
                    ->add(
                        'position',
                        null,
                        array(
                            'label' => 'admin.bladedamage.position',
                            'read_only' => true,
                        )
                    )
                    ->add(
                        'radius',
                        null,
                        array(
                            'label' => 'admin.bladedamage.radius',
                            'read_only' => true,
                        )
                    )
                    ->add(
                        'distance',
                        null,
                        array(
                            'label' => 'admin.bladedamage.distance',
                            'read_only' => true,
                        )
                    )
                    ->add(
                        'size',
                        null,
                        array(
                            'label' => 'admin.bladedamage.size',
                            'read_only' => true,
                        )
                    )
                    ->end();
            } else {
                $formMapper
                    ->with('admin.bladedamage.title', $this->getFormMdSuccessBoxArray(5))
                    ->add('windmill',
                        ModelType::class,
                        array(
                            'label' => 'admin.windmill.title',
                            'btn_add' => false,
                            'required' => false,
                            'property' => 'code',
                            'query' => $this->wmr->findEnabledandWindfarmSortedByCustomerWindfarmAndWindmillCodeQB($windfarm),
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
                    ->add(
                        'bladeDamage',
                        ModelAutocompleteType::class,
                        array(
                            'label' => 'admin.bladedamage.title',
                            'read_only' => true,
                            'disabled' => true,
                            'property' => 'damage.code',
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
        } else {
            $formMapper
                ->with('admin.bladedamage.title', $this->getFormMdSuccessBoxArray(5))
                ->add('windmill',
                    ModelType::class,
                    array(
                        'label' => 'admin.windmill.title',
                        'btn_add' => false,
                        'required' => false,
                        'property' => 'code',
                        'query' => $this->wmr->findEnabledandWindfarmSortedByCustomerWindfarmAndWindmillCodeQB($windfarm),
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
                ->add(
                    'bladeDamage',
                    ModelAutocompleteType::class,
                    array(
                        'label' => 'admin.bladedamage.title',
                        'read_only' => true,
                        'disabled' => true,
                        'property' => 'damage.code',
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
                ->end();
        }
        $formMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(7))
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
                    'read_only' => true,
                    'disabled' => true,
                )
            )
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'workOrder',
                null,
                array(
                    'label' => 'admin.workorder.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'projectNumber'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'workOrder')),
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windmillBlade',
                null,
                array(
                    'label' => 'admin.windmillblade.title',
                )
            )
            ->add('windmill',
                null,
                array(
                    'label' => 'admin.windmill.title',
                )
            )
            ->add('bladeDamage',
                null,
                array(
                    'label' => 'admin.bladedamage.title',
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
            ->add('isCompleted',
                null,
                array(
                    'label' => 'admin.workordertask.is_completed',
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
                'workOrder',
                null,
                array(
                    'label' => 'admin.workorder.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'projectNumber'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'workOrder')),
                )
            )
            ->add('isFromAudit',
                null,
                array(
                    'label' => 'admin.workorder.is_from_audit',
                )
            )
            ->add('windmillBlade',
                null,
                array(
                    'label' => 'admin.windmillblade.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'code'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windmillBlade')),
                )
            )
            ->add('windmill',
                null,
                array(
                    'label' => 'admin.windmill.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'code'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windmill')),
                )
            )
            ->add('bladeDamage',
                null,
                array(
                    'label' => 'admin.bladedamage.title',
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'damage'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'bladeDamage')),
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
            ->add('isCompleted',
                null,
                array(
                    'label' => 'admin.workordertask.is_completed',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'admin.common.action',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                )
            )
        ;
    }
}
