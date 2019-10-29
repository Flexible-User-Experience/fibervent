<?php

namespace AppBundle\Admin;

use AppBundle\Entity\DeliveryNoteTimeRegister;
use AppBundle\Enum\TimeRegisterShiftEnum;
use AppBundle\Enum\TimeRegisterTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

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
            ->add('type',
                'string',
                array(
                    'label' => 'admin.deliverynotetimeregister.type',
                    'template' => '::Admin/Cells/list__cell_delivery_note_time_register_type.html.twig',
                )
            )
            ->add('shift',
                'string',
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                    'template' => '::Admin/Cells/list__cell_delivery_note_time_register_shift.html.twig',
                )
            )
            ->add('begin',
                'date',
                array(
                    'label' => 'admin.deliverynotetimeregister.begin',
                    'format' => 'H:i',
                )
            )
            ->add('end',
                'date',
                array(
                    'label' => 'admin.deliverynotetimeregister.end',
                    'format' => 'H:i',
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
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
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
            ->add('type',
                ChoiceType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.type',
                    'choices' => TimeRegisterTypeEnum::getEnumArray(),
                    'multiple' => false,
                )
            )
            ->add('shift',
                ChoiceType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.shift',
                    'choices' => TimeRegisterShiftEnum::getEnumArray(),
                    'multiple' => false,
                )
            )
            ->add('begin',
                TimeType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.begin',
                    'minutes' => array(0, 15, 30, 45),
                )
            )
            ->add('end',
                TimeType::class,
                array(
                    'label' => 'admin.deliverynotetimeregister.end',
                    'minutes' => array(0, 15, 30, 45),
                )
            )
            ->add('totalHours',
                null,
                array(
                    'label' => 'admin.deliverynotetimeregister.total_hours',
                    'attr' => array(
                        'disabled' => true,
                    ),
                )
            )
            ->end()
        ;
    }

    /**
     * @param DeliveryNoteTimeRegister $object
     */
    public function prePersist($object)
    {
        $this->commonPreEvent($object);
    }

    /**
     * @param DeliveryNoteTimeRegister $object
     */
    public function preUpdate($object)
    {
        $this->commonPreEvent($object);
    }

    /**
     * @param DeliveryNoteTimeRegister $object
     */
    private function commonPreEvent($object)
    {
        if (!is_null($object->getBegin()) && !is_null($object->getEnd())) {
            if ($object->getBegin() instanceof \DateTime && $object->getEnd() instanceof \DateTime) {
                if ($object->getBegin()->format('H:i') < $object->getEnd()->format('H:i')) {
                    $time1 = strtotime($object->getBegin()->format('H:i:s'));
                    $time2 = strtotime($object->getEnd()->format('H:i:s'));
                    $object->setTotalHours(round(abs($time2 - $time1) / 3600, 2));
                }
            }
        }
    }
}
