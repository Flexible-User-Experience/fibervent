<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class BladeAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class BladeAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.blade.title';
    protected $baseRoutePattern = 'windfarms/blade';
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'model',
                null,
                array(
                    'label'    => 'admin.blade.model',
                    'required' => true,
                )
            )
            ->add(
                'length',
                null,
                array(
                    'label'       => 'admin.blade.length',
                    'required'    => true,
                    'help'        => 'm',
                    'sonata_help' => 'm',
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
                'length',
                null,
                array(
                    'label' => 'admin.blade.length',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'admin.common.enabled',
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
                    'label'    => 'admin.blade.model',
                    'editable' => true,
                )
            )
            ->add(
                'length',
                null,
                array(
                    'label'    => 'admin.blade.length',
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
