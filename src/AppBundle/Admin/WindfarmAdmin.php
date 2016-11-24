<?php

namespace AppBundle\Admin;

use AppBundle\Enum\WindfarmLanguageEnum;
use Oh\GoogleMapFormTypeBundle\Form\Type\GoogleMapType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
        $collection
            ->add('audits', $this->getRouterIdParameter() . '/audits')
            ->add('map', $this->getRouterIdParameter() . '/map')
            ->add('excel', $this->getRouterIdParameter() . '/excel')
//            ->add('excel', $this->getRouterIdParameter() . '/excel', array(
//                '_format' => 'xls'
//            ), array(
//                '_format' => 'csv|xls|xlsx'
//            ))
            ->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $customer = null;
        if ($this->getSubject() !== null) {
            $customer = $this->getSubject()->getCustomer();
        }

        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'customer',
                'sonata_type_model',
                array(
                    'label'        => 'Client',
                    'required'     => true,
                    'multiple'     => false,
                    'btn_add'      => false,
                    'query'        => $this->cr->findEnabledSortedByNameQ(),
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label'    => 'CIF',
                    'required' => false,
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
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
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
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'manager',
                'sonata_type_model',
                array(
                    'label'      => 'O&M Regional Manager',
                    'btn_add'    => false,
                    'btn_delete' => false,
                    'required'   => false,
                    'property'   => 'contactInfoString',
                    'query'      => $this->ur->findEnabledSortedByNameQ($customer),
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
            ->add(
                'language',
                ChoiceType::class,
                array(
                    'label'    => 'Idioma PDF',
                    'choices'  => WindfarmLanguageEnum::getEnumArrayString(),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
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
                null,
                array(
                    'label' => 'Província',
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label' => 'O&M Regional Manager',
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
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
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
                    'label'                            => 'O&M Regional Manager',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'lastname'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'manager')),
                    'template'                         => '::Admin/Cells/list__windfarm_manager_fullname.html.twig',
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
                        'audits' => array('template' => '::Admin/Buttons/list__action_audits_button.html.twig'),
                        'excel' => array('template'  => '::Admin/Buttons/list__action_excel_button.html.twig'),
                        'map'    => array('template' => '::Admin/Buttons/list__action_map_button.html.twig'),
                    )
                )
            );
    }
}
