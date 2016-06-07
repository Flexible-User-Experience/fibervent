<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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
                null,
                array(
                    'label' => 'Província',
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
                'year',
                null,
                array(
                    'label' => 'Any',
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
            ->add(
                'customer',
                null,
                array(
                    'label'    => 'Client',
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
            ->with('Geolocalització', $this->getFormMdSuccessBoxArray(12))
            ->add('latLng', 'oh_google_maps', array('label' => 'Mapa', 'required' => false))
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
                'manager',
                null,
                array(
                    'label' => 'Administrador',
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label' => 'Client',
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
                'state',
                null,
                array(
                    'label'    => 'Província',
                    'editable' => true,
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label'    => 'Administrador',
                    'editable' => true,
                )
            )
            ->add(
                'customer',
                null,
                array(
                    'label'    => 'Client',
                    'editable' => true,
                )
            )
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
