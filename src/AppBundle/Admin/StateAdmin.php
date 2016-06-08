<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class StateAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class StateAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Província';
    protected $baseRoutePattern = 'customers/state';
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
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'name',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'country',
                'sonata_type_model',
                array(
                    'label'      => 'Country',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->cr->findAllSortedByCodeQ(),
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
                    'label' => 'Nom',
                )
            )
            ->add(
                'country',
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
                'country',
                null,
                array(
                    'label' => 'País',
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
