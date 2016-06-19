<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

/**
 * Class CustomerAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class CustomerAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Client';
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
        parent::configureRoutes($collection);
        $collection->add('map', $this->getRouterIdParameter() . '/map');
    }

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
            ->end()
            ->with('Contacte', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'email',
                EmailType::class,
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
                UrlType::class,
                array(
                    'label'       => 'Web',
                    'required'    => false,
                    'help'        => 'http://...',
                    'sonata_help' => 'http://...',
                )
            )
            ->end()
            ->with('Dades Postals', $this->getFormMdSuccessBoxArray(4))
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
                    'query'      => $this->sr->findAllSortedByNameQ(),
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Contactes', $this->getFormMdSuccessBoxArray(8))
                ->add(
                    'contacts',
                    'sonata_type_model',
                    array(
                        'label'        => ' ',
                        'property'     => 'fullContactInfoString',
                        'required'     => false,
                        'multiple'     => true,
                        'btn_add'      => false,
                        'by_reference' => false,
                        'query'        => $this->ur->findOnlyAvailableSortedByNameQ($this->getSubject()),
                    )
                )
                ->end()
                ->with('Parcs Eòlics', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'windfarms',
                    'sonata_type_model',
                    array(
                        'label'        => ' ',
                        'required'     => false,
                        'multiple'     => true,
                        'btn_add'      => false,
                        'by_reference' => false,
                        'query'        => $this->wfr->findOnlyAvailableSortedByNameQ($this->getSubject()),
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
                    'query' => $this->sr->findAllSortedByNameQ(),
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
                'email',
                null,
                array(
                    'label'    => 'Correu Electrònic',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label'    => 'Telèfon',
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
