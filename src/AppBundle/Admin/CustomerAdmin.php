<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
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
    protected $classnameLabel = 'admin.customer.title';
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
        $collection
            ->add('map', $this->getRouterIdParameter() . '/map')
            ->remove('delete')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'imageFile',
                'file',
                array(
                    'label'    => 'admin.customer.image',
                    'help'     => $this->getImageHelperFormMapperWithThumbnail(250),
                    'required' => false,
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
                    'label'    => 'admin.common.enabled',
                )
            )
            ->end()
            ->with('admin.customer.contact', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'email',
                EmailType::class,
                array(
                    'label'    => 'admin.customer.email',
                    'required' => false,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'admin.customer.phone',
                )
            )
            ->add(
                'web',
                UrlType::class,
                array(
                    'label'       => 'admin.customer.web',
                    'required'    => false,
                    'help'        => 'http://...',
                    'sonata_help' => 'http://...',
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
                ModelType::class,
                array(
                    'label'      => 'admin.customer.state',
                    'btn_add'    => true,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->sr->findAllSortedByNameQ(),
                    'choices_as_values' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('admin.customer.contacts', $this->getFormMdSuccessBoxArray(8))
                ->add(
                    'contacts',
                    ModelType::class,
                    array(
                        'label'        => ' ',
                        'property'     => 'fullContactInfoString',
                        'required'     => false,
                        'multiple'     => true,
                        'btn_add'      => false,
                        'by_reference' => false,
                        'query'        => $this->ur->findOnlyAvailableSortedByNameQ($this->getSubject()),
                        'choices_as_values' => true,
                    )
                )
                ->end()
                ->with('admin.customer.windfarms', $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'windfarms',
                    ModelType::class,
                    array(
                        'label'        => ' ',
                        'required'     => false,
                        'multiple'     => true,
                        'btn_add'      => false,
                        'by_reference' => false,
                        'query'        => $this->wfr->findOnlyAvailableSortedByNameQ($this->getSubject()),
                        'choices_as_values' => true,
                    )
                )
                ->end()
            ;
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
                'email',
                null,
                array(
                    'label' => 'admin.customer.email',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'admin.customer.phone',
                )
            )
            ->add(
                'web',
                null,
                array(
                    'label' => 'admin.customer.web',
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
                    'query' => $this->sr->findAllSortedByNameQ(),
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'admin.common.enabled',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'photo',
                null,
                array(
                    'label'    => 'admin.customer.image',
                    'template' => '::Admin/Cells/list__cell_customer_image_field.html.twig',
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label'    => 'admin.customer.code',
                    'editable' => true,
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
                'email',
                null,
                array(
                    'label'    => 'admin.customer.email',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label'    => 'admin.customer.phone',
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
                        'map'    => array('template' => '::Admin/Buttons/list__action_map_button.html.twig'),
                    )
                )
            );
    }
}
