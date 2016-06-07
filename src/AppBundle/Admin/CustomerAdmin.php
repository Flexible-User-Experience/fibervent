<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

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
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $sr = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('AppBundle:State');
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'code',
                null,
                array(
                    'label' => 'CIF',
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
                'address',
                null,
                array(
                    'label' => 'Adreça',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'Codi Postal',
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
                    'query'      => $sr->findAllSortedByNameQ(),
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
                    'label' => 'Correu Electrònic',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon',
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
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Contactes', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'contacts',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'cascade_validation' => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                        'sortable' => 'position',
                    )
                )
                ->end()
                ->with('Parcs Eòlics', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'windfarms',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'cascade_validation' => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                        'sortable' => 'position',
                    )
                )
                ->end();
        }
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
                    'label' => 'CIF',
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
                'email',
                null,
                array(
                    'label' => 'Correu Electrònic',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon',
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
                    'label' => 'Adreça',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'Codi Postal',
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
                'code',
                null,
                array(
                    'label'    => 'CIF',
                    'editable' => true,
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
                'state',
                null,
                array(
                    'label'    => 'Província',
                    'editable' => true,
                )
            )
//            ->add(
//                'address',
//                null,
//                array(
//                    'label' => 'Adreça',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'phone',
//                null,
//                array(
//                    'label' => 'Telèfon',
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
