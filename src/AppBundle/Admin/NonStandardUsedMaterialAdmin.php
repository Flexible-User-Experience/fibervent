<?php

namespace AppBundle\Admin;

use AppBundle\Enum\NonStandardUsedMaterialItemEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->getRootCode() == $this->getCode()) {
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'deliveryNote',
                    null,
                    array(
                        'label' => 'admin.deliverynote.title',
                        // TODO apply query builder strategy
                    )
                )
                ->end()
            ;
        } else {
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'deliveryNote',
                    null,
                    array(
                        'label' => 'admin.deliverynote.title',
                        // TODO apply query builder strategy
                        'attr' => array(
                            'hidden' => true,
                        ),
                    )
                )
                ->end()
            ;
        }
        $formMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
            ->add('item',
                ChoiceType::class,
                array(
                    'label' => 'admin.nonstandardusedmaterial.item',
                    'choices' => NonStandardUsedMaterialItemEnum::getEnumArray(),
                    'multiple' => false,
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
                'deliveryNote',
                null,
                array(
                    'label' => 'admin.deliverynote.title',
                )
                // TODO apply query builder to improve filter selector
            )
            ->add('item',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.item',
                ),
                'choice',
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => NonStandardUsedMaterialItemEnum::getEnumArray(),
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
            ->add('item',
                'string',
                array(
                    'label' => 'admin.nonstandardusedmaterial.item',
                    'template' => '::Admin/Cells/list__cell_non_standard_used_material_item.html.twig',
                )
            )
            ->add('quantity',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.quantity',
                    'editable' => true,
                )
            )
            ->add('description',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.description',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'admin.common.action',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                )
            )
        ;
    }
}
