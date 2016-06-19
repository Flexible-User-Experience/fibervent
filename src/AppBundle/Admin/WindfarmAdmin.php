<?php

namespace AppBundle\Admin;

use Oh\GoogleMapFormTypeBundle\Form\Type\GoogleMapType;
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
    protected $classnameLabel = 'Parc Eòlic';
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
        parent::configureRoutes($collection);
        $collection->add('map', $this->getRouterIdParameter() . '/map');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'customer',
                null,
                array(
                    'label'    => 'Client',
                    'required' => true,
                )
            )
            ->add(
                'customer',
                'sonata_type_model',
                array(
                    'label'        => 'Client',
                    'required'     => true,
                    'multiple'     => false,
                    'btn_add'      => false,
                    'query'        => $this->cr->findAllSortedByNameQ(),
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Ciutat',
                )
            )
            ->add(
                'state',
                'sonata_type_model',
                array(
                    'label'      => 'Província',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->sr->findAllSortedByNameQ(),
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'manager',
                null,
                array(
                    'label'    => 'Administrador',
                    'required' => false,
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label'    => 'Any',
                    'required' => false
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label'       => 'Potència',
                    'required'    => false,
                    'help'        => 'MW',
                    'sonata_help' => 'MW',
                )
            )
            ->end()
            ->with('Geolocalització', $this->getFormMdSuccessBoxArray(12))
            ->add(
                'latLng',
                GoogleMapType::class,
                array(
                    'label'    => 'Mapa',
                    'required' => false
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
                'customer',
                null,
                array(
                    'label' => 'Client',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'Ciutat',
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label' => 'Administrador',
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
                'year',
                null,
                array(
                    'label' => 'Any',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'Província',
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
                'customer',
                null,
                array(
                    'label'                            => 'Client',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'customer')),
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label'    => 'Nom',
                    'editable' => true,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label'    => 'Ciutat',
                    'editable' => true,
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label'                            => 'Administrador',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'lastname'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'manager')),
                    'template'                         => '::Admin/Cells/list__windfarm_manager_fullname.html.twig',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'Accions',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'map'    => array('template' => '::Admin/Buttons/list__action_map_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }
}
