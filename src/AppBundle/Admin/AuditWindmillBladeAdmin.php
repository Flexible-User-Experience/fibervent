<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class AuditWindmillBladeAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class AuditWindmillBladeAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Auditoria Pala Aerogenerador';
    protected $baseRoutePattern = 'audits/audit-windmill-blade';
    protected $datagridValues = array(
        '_sort_by'    => 'audit',
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
                'audit',
                null,
                array(
                    'label'    => 'Auditoria',
                    'required' => true,
                )
            )
            ->add(
                'windmillBlade',
                null,
                array(
                    'label'    => 'Pala',
                    'required' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Danys', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'bladeDamages',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'cascade_validation' => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                    )
                )
                ->end();
        }
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'audit',
                null,
                array(
                    'label'    => 'Auditoria',
                    'editable' => true,
                )
            )
            ->add(
                'windmillBlade',
                null,
                array(
                    'label'    => 'Pala',
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
