<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use AppBundle\Enum\UserRolesEnum;
use AppBundle\Enum\WindfarmLanguageEnum;
use Doctrine\ORM\QueryBuilder;
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
    protected $classnameLabel = 'admin.windfarm.title';
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
            ->add('excelAttachment', $this->getRouterIdParameter() . '/excel-attachment', array(
                '_format' => 'xls'
            ), array(
                '_format' => 'csv|xls|xlsx'
            ))
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'customer',
                'sonata_type_model',
                array(
                    'label'        => 'admin.windfarm.customer',
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
                    'label'    => 'admin.customer.code',
                    'required' => false,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'admin.customer.name',
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
            ->with('admin.customer.postal_data', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'address',
                null,
                array(
                    'label' => 'admin.customer.address',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'admin.customer.zip',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'admin.customer.city',
                )
            )
            ->add(
                'state',
                'sonata_type_model',
                array(
                    'label'      => 'admin.customer.state',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->sr->findAllSortedByNameQ(),
                )
            )
            ->end()
            ->with('admin.common.controls', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'manager',
                'sonata_type_model',
                array(
                    'label'      => 'admin.windfarm.manager',
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
                    'label'    => 'admin.windfarm.year',
                    'required' => false
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label'       => 'admin.turbine.power',
                    'required'    => false,
                )
            )
            ->add(
                'windmillAmount',
                null,
                array(
                    'label'    => 'admin.windfarm.windmill_amount',
                    'required' => false
                )
            )
            ->add(
                'language',
                ChoiceType::class,
                array(
                    'label'    => 'admin.windfarm.pdf_language',
                    'choices'  => WindfarmLanguageEnum::getEnumArrayString(),
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                )
            )
            ->end()
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
                'customer',
                null,
                array(
                    'label' => 'admin.windfarm.customer',
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label' => 'admin.customer.code',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'admin.customer.name',
                )
            )
            ->add(
                'address',
                null,
                array(
                    'label' => 'admin.customer.address',
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'admin.customer.zip',
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'admin.customer.city',
                )
            )
            ->add(
                'state',
                null,
                array(
                    'label' => 'admin.customer.state',
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label' => 'admin.windfarm.manager',
                )
            )
            ->add(
                'power',
                null,
                array(
                    'label' => 'admin.turbine.power',
                )
            )
            ->add(
                'year',
                null,
                array(
                    'label' => 'admin.windfarm.year',
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
                'customer',
                null,
                array(
                    'label'                            => 'admin.windfarm.customer',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'customer')),
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label'    => 'admin.customer.name',
                    'editable' => true,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label'    => 'admin.customer.city',
                    'editable' => true,
                )
            )
            ->add(
                'manager',
                null,
                array(
                    'label'                            => 'admin.windfarm.manager',
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
                        'audits' => array('template' => '::Admin/Buttons/list__action_audits_button.html.twig'),
                        'excel' => array('template'  => '::Admin/Buttons/list__action_excel_button.html.twig'),
                        'map'    => array('template' => '::Admin/Buttons/list__action_map_button.html.twig'),
                    )
                )
            );
    }

    /**
     * @param string $context
     *
     * @return QueryBuilder
     */
    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        // Customer filter
        if ($this->acs->isGranted(UserRolesEnum::ROLE_CUSTOMER) && !$this->acs->isGranted(UserRolesEnum::ROLE_OPERATOR)) {
            /** @var User $user */
            $user = $this->tss->getToken()->getUser();
            $query
                ->andWhere($query->getRootAliases()[0].'.customer = :customer')
                ->setParameter('customer', $user->getCustomer())
            ;
        }

        return $query;
    }
}
