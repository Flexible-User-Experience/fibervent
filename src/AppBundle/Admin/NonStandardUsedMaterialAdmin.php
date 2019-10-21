<?php

namespace AppBundle\Admin;

use AppBundle\Enum\NonStandardUsedMaterialItemEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
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
        if ($this->getRootCode() == $this->getCode()) {
            $formMapper
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'deliveryNote',
                    null,
                    array(
                        'label' => 'admin.deliverynote.title',
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
            ->add('item',
                null,
                array(
                    'label' => 'admin.nonstandardusedmaterial.type',
                    'template' => '::Admin/Cells/list__non_standard_used_material_item.html.twig',
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
}
