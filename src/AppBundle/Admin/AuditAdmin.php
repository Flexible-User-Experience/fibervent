<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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
                null,
                array(
                    'label'    => 'Eines',
                    'required' => true,
                )
            )
            ->add(
                'observations',
                null,
                array(
                    'label'    => 'Observacions',
                    'required' => true,
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
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
                'date',
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/m/Y H:i',
                )
            )
            ->add(
                'endDate',
                'date',
                array(
                    'label'  => 'Data fi',
                    'format' => 'd/m/Y H:i',
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
                'date',
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/m/Y'
                )
            )
//            ->add(
//                'endDate',
//                'date',
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
