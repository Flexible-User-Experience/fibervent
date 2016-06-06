<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class TurbineAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class TurbineAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Turbine';
    protected $baseRoutePattern = 'windfarms/windmill/turbine';
    protected $datagridValues = array(
        '_sort_by'    => 'model',
        '_sort_order' => 'desc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'model',
                null,
                array(
                    'label'    => 'Model',
                    'required' => true,
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label'    => 'Alçada',
                    'required' => true,
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label'    => 'Diàmetre',
                    'required' => true,
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potència',
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label'    => 'Actiu',
                    'required' => false,
                )
            )
            ->end();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'model',
                null,
                array(
                    'label'    => 'Model',
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label'    => 'Alçada',
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label'    => 'Diàmetre',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potència',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'Actiu',
                    'editable' => true,
                )
            );
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'model',
                null,
                array(
                    'label'    => 'Model',
                    'editable' => true,
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label'    => 'Alçada',
                    'editable' => true,
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label'    => 'Diàmetre',
                    'editable' => true,
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potència',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'Accions',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }
}
