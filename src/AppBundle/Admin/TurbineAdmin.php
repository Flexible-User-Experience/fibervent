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
    protected $classnameLabel = 'admin.turbine.title';
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'model',
                null,
                array(
                    'label'    => 'admin.blade.model',
                    'required' => true,
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label'       => 'admin.turbine.height',
                    'required'    => true,
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label'       => 'admin.turbine.diameter',
                    'required'    => true,
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label'       => 'admin.turbine.power',
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
                    'label' => 'admin.blade.model',
                )
            )
            ->add(
                'towerHeight',
                null,
                array(
                    'label' => 'admin.turbine.height',
                )
            )
            ->add(
                'rotorDiameter',
                null,
                array(
                    'label' => 'admin.turbine.diameter',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'admin.turbine.power',
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
                    'label'    => 'admin.blade.model',
                    'editable' => true,
                )
            )
            ->add(
                'rotorDiameter',
                'decimal',
                array(
                    'label'    => 'admin.turbine.diameter',
                    'editable' => true,
                )
            )
            ->add(
                'power',
                'decimal',
                array(
                    'label'    => 'admin.turbine.power',
                    'editable' => true,
                )
            )
            ->add(
                'towerHeight',
                'decimal',
                array(
                    'label'    => 'admin.turbine.height',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'admin.common.action',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }
}
