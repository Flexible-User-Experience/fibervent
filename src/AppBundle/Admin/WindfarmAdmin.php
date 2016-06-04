<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class WindfarmAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindfarmAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Windfarm';
    protected $baseRoutePattern = 'windfarms/windfarm';
    protected $datagridValues = array(
        '_sort_by'    => 'name',
        '_sort_order' => 'asc',
    );

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('batch');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nombre',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Ciudad',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Província',
                )
            )
            ->add(
                'state.country',
                null,
                array(
                    'label' => 'País',
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'Año',
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'enabled',
                'checkbox',
                array(
                    'label'    => 'Actiu',
                    'required' => false,
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'Cliente',
                )
            )
            ->add(
                'gpsLongitude',
                null,
                array(
                    'label' => 'Longitud',
                )
            )
            ->add(
                'gpsLatitude',
                null,
                array(
                    'label' => 'Latitud',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potencia',
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
                'name',
                null,
                array(
                    'label' => 'Nombre',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Ciudad',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'Potencia',
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'Año',
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'Cliente',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Província',
                )
            )
            ->add(
                'state.country',
                null,
                array(
                    'label' => 'País',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
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
                'name',
                null,
                array(
                    'label' => 'Nombre',
                    'editable' => true,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Ciudad',
                    'editable' => true,
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Província',
                    'editable' => true,
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'Cliente',
                    'editable' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
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
                        'show' => array('template' => '::Admin/Buttons/list__action_show_button.html.twig'),
                    )
                )
            );
    }
}
