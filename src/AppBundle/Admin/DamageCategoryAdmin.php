<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class DamageCategoryAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class DamageCategoryAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Categoria Dany';
    protected $baseRoutePattern = 'audits/damage-category';
    protected $datagridValues = array(
        '_sort_by'    => 'category',
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
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'colour',
                'color_picker',
                array(
                    'label'    => 'Color',
                    'required' => true,
                    'picker_options' => array(
                        'color'    => false,
                        'mode'     => 'hsl',
                        'hide'     => false,
                        'border'   => true,
                        'target'   => false,
                        'width'    => 200,
                        'palettes' => true,
                        'controls' => array(
                            'horiz' => 's',
                            'vert'  => 'l',
                            'strip' => 'h'
                        )
                    )
                )
            )
            ->add(
                'category',
                null,
                array(
                    'label'    => 'Categoria',
                    'required' => true,
                )
            )
            ->add(
                'priority',
                null,
                array(
                    'label'    => 'Prioritat',
                    'required' => true,
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label'    => 'Descripció',
                    'required' => true,
                )
            )
            ->add(
                'recommendedAction',
                null,
                array(
                    'label'    => 'Acció Recomanada',
                    'required' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'Actiu',
                )
            )
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'fakecolour',
                null,
                array(
                    'label'    => ' ',
                    'template' => '::Admin/Cells/list__cell_colour.html.twig',
                    'editable' => false,
                )
            )
            ->add(
                'category',
                null,
                array(
                    'label'    => 'Categoria',
                    'editable' => true,
                )
            )
            ->add(
                'priority',
                null,
                array(
                    'label'    => 'Prioritat',
                    'editable' => true,
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label'    => 'Descripció',
                    'editable' => true,
                )
            )
            ->add(
                'recommendedAction',
                null,
                array(
                    'label'    => 'Acció Recomanada',
                    'editable' => true,
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
                    )
                )
            );
    }
}
