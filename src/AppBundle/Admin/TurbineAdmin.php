<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class TurbineAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class TurbineAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Turbina';
    protected $baseRoutePattern = 'windfarms/turbine';
    protected $datagridValues = array(
        '_sort_by'    => 'model',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

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
                    'label'       => 'Alçada',
                    'required'    => true,
                    'help'        => 'm',
                    'sonata_help' => 'm',
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label'       => 'Diàmetre',
                    'required'    => true,
                    'help'        => 'm',
                    'sonata_help' => 'm',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label'       => 'Potència',
                    'help'        => 'MW',
                    'sonata_help' => 'MW',
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
                    'label' => 'Model',
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label' => 'Alçada',
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label' => 'Diàmetre',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potència',
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
                'rotorDiameter',
                'decimal',
                array(
                    'label'    => 'Diàmetre (m)',
                    'editable' => true,
                )
            )
            ->add(
                'power',
                'decimal',
                array(
                    'label'    => 'Potència (MW)',
                    'editable' => true,
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label'    => 'Alçada (m)',
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
