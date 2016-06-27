<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

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
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
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
                'recommendedAction',
                null,
                array(
                    'label'    => 'Acció Recomanada',
                    'required' => true,
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
