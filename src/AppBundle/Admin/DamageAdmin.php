<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class DamageAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class DamageAdmin extends AbstractBaseAdmin
{
    protected $maxPerPage = 50;
    protected $classnameLabel = 'admin.damage.title';
    protected $baseRoutePattern = 'audits/damage';
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'section',
                null,
                array(
                    'label'    => 'admin.damage.section',
                    'required' => true,
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label'    => 'admin.damage.code',
                    'required' => true,
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label'    => 'admin.damage.description',
                    'required' => true,
                )
            )
            ->end()
            ->with('admin.common.controls', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'admin.common.enabled',
                    'required' => false,
                )
            )
            ->end()
            ->with('admin.damage.translations', $this->getFormMdSuccessBoxArray(6))
            ->add('translations', 'a2lix_translations_gedmo', array(
                'required' => false,
                'label' => ' ',
                'translatable_class' => 'AppBundle\Entity\Translation\DamageTranslation',
                'fields' => array(
                    'description' => array('label' => 'admin.damage.description', 'attr' => array('rows' => 8), 'required' => false),
                ),
            ))
            ->end()
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
                'section',
                null,
                array(
                    'label'    => 'admin.damage.section',
                    'editable' => true,
                )
            )
            ->add(
                'code',
                null,
                array(
                    'label'    => 'admin.damage.code',
                    'editable' => true,
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label'    => 'admin.damage.description',
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
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    )
                )
            );
    }
}
