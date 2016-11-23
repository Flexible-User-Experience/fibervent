<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

/**
 * Class StateAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class StateAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.state.title';
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'name',
                null,
                array(
                    'label' => 'admin.customer.name',
                )
            )
            ->add(
                'countryCode',
                CountryType::class,
                array(
                    'label' => 'admin.state.country',
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
                    'label' => 'admin.customer.name',
                )
            )
            ->add(
                'countryCode',
                null,
                array(
                    'label' => 'admin.state.country',
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
                'name',
                null,
                array(
                    'label'    => 'admin.customer.name',
                    'editable' => true,
                )
            )
            ->add(
                'countryName',
                null,
                array(
                    'label' => 'admin.state.country',
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
