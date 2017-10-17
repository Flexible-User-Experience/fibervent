<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Windmill;
use AppBundle\Entity\WindmillBlade;
use Oh\GoogleMapFormTypeBundle\Form\Type\GoogleMapType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\CollectionType;

/**
 * Class WindmillAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class WindmillAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.windmillblade.title';
    protected $baseRoutePattern = 'windfarms/windmill';
    protected $datagridValues = array(
        '_sort_by'    => 'code',
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
        $collection->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'windfarm',
                ModelType::class,
                array(
                    'label'    => 'admin.windmill.windfarm',
                    'btn_add'  => false,
                    'required' => true,
                    'query'    => $this->wfr->findEnabledSortedByNameQ(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label' => 'admin.windmill.code',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'admin.common.enabled',
                )
            )
            ->end()
            ->with('admin.common.controls', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'turbine',
                ModelType::class,
                array(
                    'label'      => 'admin.windmill.turbine',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->tr->findAllSortedByModelQ(),
                    'choices_as_values' => true,
                )
            )
            ->add(
                'bladeType',
                ModelType::class,
                array(
                    'label'      => 'admin.windmill.bladetype',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->br->findAllSortedByModelQ(),
                    'choices_as_values' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('admin.windmill.blades', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'windmillBlades',
                    CollectionType::class,
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
            ->with('admin.windfarm.geoposition', $this->getFormMdSuccessBoxArray(12))
            ->add(
                'latLng',
                GoogleMapType::class,
                array(
                    'label'    => 'admin.windfarm.latlng',
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
                'windfarm.customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                )
            )
            ->add(
                'windfarm',
                null,
                array(
                    'label' => 'admin.windmill.windfarm',
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label' => 'admin.windmill.code',
                )
            )
            ->add(
                'turbine',
                null,
                array(
                    'label' => 'admin.windmill.turbine',
                )
            )
            ->add(
                'bladeType',
                null,
                array(
                    'label' => 'admin.windmill.bladetype',
                )
            )
            ->add(
                'windfarm.manager',
                null,
                array(
                    'label' => 'admin.windmill.manager',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'admin.common.enabled',
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
                'windfarm',
                null,
                array(
                    'label'    => 'admin.windmill.windfarm',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windfarm')),
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label'    => 'admin.windmill.code',
                    'editable' => true,
                )
            )
            ->add(
                'turbine',
                null,
                array(
                    'label'    => 'admin.windmill.turbine',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'model'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'turbine')),
                )
            )
            ->add(
                'bladeType',
                null,
                array(
                    'label'    => 'admin.windmill.bladetype',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'model'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'bladeType')),
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'admin.common.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'admin.common.action',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    )
                )
            );
    }

    /**
     * Every new Windmill persist 3, and only 3 new linked blades
     *
     * @param Windmill $object
     */
    public function prePersist($object)
    {
        $blade1 = new WindmillBlade();
        $blade1
            ->setWindmill($object)
            ->setOrder(1);
        $blade2 = new WindmillBlade();
        $blade2
            ->setWindmill($object)
            ->setOrder(2);
        $blade3 = new WindmillBlade();
        $blade3
            ->setWindmill($object)
            ->setOrder(3);
        $object
            ->addWindmillBlade($blade1)
            ->addWindmillBlade($blade2)
            ->addWindmillBlade($blade3);
    }
}
