<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class CustomerAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class CustomerAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Customer';
    protected $baseRoutePattern = 'customers/customer';
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
                'code',
                null,
                array(
                    'label' => 'Código',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nombre',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'Dirección',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'CP',
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
                'email',
                null,
                array(
                    'label' => 'Correo electrónico',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Teléfono',
                )
            )
            ->add(
                'web',
                null,
                array(
                    'label' => 'Web',
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
                    'label' => 'Código',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nombre',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'Correo electrónico',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Teléfono',
                )
            )
            ->add(
                'web',
                null,
                array(
                    'label' => 'Web',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'Dirección',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'CP',
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
//            ->add(
//                'code',
//                null,
//                array(
//                    'label' => 'Código',
//                    'editable' => true,
//                )
//            )
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
//            ->add(
//                'address',
//                null,
//                array(
//                    'label' => 'Dirección',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'phone',
//                null,
//                array(
//                    'label' => 'Teléfono',
//                    'editable' => true,
//                )
//            )
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
