<?php

namespace AppBundle\Admin;

use Oh\GoogleMapFormTypeBundle\Form\Type\GoogleMapType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class WindmillAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindmillAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Windmill';
    protected $baseRoutePattern = 'windfarms/windmill';
    protected $datagridValues = array(
        '_sort_by'    => 'code',
        '_sort_order' => 'asc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'code',
                null,
                array(
                    'label' => 'Codi',
                )
            )
            ->add(
                'windfarm',
                null,
                array(
                    'label'    => 'Parc Eòlic',
                    'required' => true,
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'turbine',
                'sonata_type_model',
                array(
                    'label'      => 'Turbina',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                )
            )
            ->add(
                'bladeType',
                'sonata_type_model',
                array(
                    'label'      => 'Tipus Pala',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                )
            )
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label'    => 'Actiu',
                    'required' => false,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Pales', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'windmillBlades',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'btn_add'            => false,
                        'cascade_validation' => true,
                        'type_options'       => array(
                            'delete' => false,
                        )
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                        'sortable' => 'position',
                    )
                )
                ->end();
        }
        $formMapper
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
                'code',
                null,
                array(
                    'label' => 'Codi',
                )
            )
            ->add(
                'windfarm',
                null,
                array(
                    'label' => 'Parc Eòlic',
                )
            )
            ->add(
                'turbine',
                null,
                array(
                    'label'    => 'Turbina',
                )
            )
            ->add(
                'windfarm.manager',
                null,
                array(
                    'label' => 'Administrador',
                )
            )
            ->add(
                'windfarm.customer',
                null,
                array(
                    'label' => 'Client',
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
                'code',
                null,
                array(
                    'label'    => 'Codi',
                    'editable' => true,
                )
            )
            ->add(
                'windfarm',
                null,
                array(
                    'label'    => 'Parc Eòlic',
                    'editable' => true,
                )
            )
            ->add(
                'turbine',
                null,
                array(
                    'label'    => 'Turbina',
                    'editable' => true,
                )
            )
//            ->add(
//                'gpsLongitude',
//                null,
//                array(
//                    'label' => 'Longitud',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'gpsLatitude',
//                null,
//                array(
//                    'label' => 'Latitud',
//                    'editable' => true,
//                )
//            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'Actiu',
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
