<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class AuditAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Auditoria';
    protected $baseRoutePattern = 'audits/audit';
    protected $datagridValues = array(
        '_sort_by'    => 'beginDate',
        '_sort_order' => 'desc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'beginDate',
                'sonata_type_date_picker',
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'endDate',
                'sonata_type_date_picker',
                array(
                    'label'  => 'Data fi',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label'    => 'Estat',
                    'required' => true,
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label'    => 'Tipus',
                    'required' => true,
                )
            )
            ->add(
                'tools',
                textareatype::class,
                array(
                    'label'    => 'Eines',
                    'required' => true,
                )
            )
            ->add(
                'observations',
                textareatype::class,
                array(
                    'label'    => 'Observacions',
                    'required' => true,
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'windmill',
                null,
                array(
                    'label'    => 'Aerogenerador',
                    'required' => true,
                )
            )
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label'    => 'Actiu',
                    'required' => false,
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
                'beginDate',
                'doctrine_orm_date',
                array(
                    'label'  => 'Data inici',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'endDate',
                'doctrine_orm_date',
                array(
                    'label'  => 'Data fi',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label' => 'Estat',
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label' => 'Tipus',
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
                'beginDate',
                null,
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/m/Y'
                )
            )
//            ->add(
//                'endDate',
//                null,
//                array(
//                    'label'  => 'Data fi',
//                    'format' => 'd/m/Y'
//                )
//            )
            ->add(
                'status',
                null,
                array(
                    'label'    => 'Estat',
                    'editable' => true,
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label'    => 'Tipus',
                    'editable' => true,
                )
            )
            ->add(
                'windmill.code',
                null,
                array(
                    'label'    => 'Aerogenerador',
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
