<?php

namespace AppBundle\Admin;

use AppBundle\Form\Type\ActionButtonFormType;
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
            ->with('General', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'audit',
                null,
                array(
                    'label'    => 'Auditoria',
                    'required' => true,
                    'attr'     => array(
                        'hidden' => true,
                    ),
                )
            )
            ->add(
                'windmillBlade',
                null,
                array(
                    'label'    => 'Pala',
                    'required' => true,
                    'disabled' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Danys', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'fakeAction',
                    ActionButtonFormType::class,
                    array(
                        'text'     => 'Editar danys',
                        'url'      => 'http://www.flux.cat',
                        'label'    => 'Accions',
                        'mapped'   => false,
                        'required' => false,
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
