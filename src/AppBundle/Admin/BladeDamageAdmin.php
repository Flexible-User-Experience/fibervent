<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class BladeDamageAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class BladeDamageAdmin extends AbstractBaseAdmin
{
    protected $maxPerPage = 50;
    protected $classnameLabel = 'Dany Pala';
    protected $baseRoutePattern = 'audits/blade-damage';
    protected $datagridValues = array(
        '_sort_by'    => 'status',
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
                'number',
                null,
                array(
                    'label'    => 'Num. Dany',
                    'required' => true,
                )
            )
            ->add(
                'damage',
                null,
                array(
                    'label'    => 'Codi',
                    'required' => true,
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label'    => 'Posició',
                    'required' => true,
                )
            )
            ->add(
                'radius',
                null,
                array(
                    'label'    => 'Radi',
                    'required' => true,
                    'help'        => 'm',
                    'sonata_help' => 'm',
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label'       => 'Distància',
                    'required'    => true,
                    'help'        => 'm',
                    'sonata_help' => 'm',
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label'    => 'Dimensió',
                    'required' => true,
                )
            )
            ->add(
                'damageCategory',
                null,
                array(
                    'label'    => 'Categoria',
                    'required' => true,
                )
            )
            ->add(
                'auditWindmillBlade',
                null,
                array(
                    'label'    => 'Pala',
                    'required' => true,
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
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Fotos', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'photos',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'cascade_validation' => true,
                    ),
                    array(
                        'edit'   => 'inline',
                        'inline' => 'table',
                    )
                )
                ->end();
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'position',
                null,
                array(
                    'label' => 'Posició',
                )
            )
            ->add(
                'radius',
                null,
                array(
                    'label' => 'Radi',
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label' => 'Distància',
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label' => 'Mesura',
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
//            ->add(
//                'damage.code',
//                null,
//                array(
//                    'label'    => 'Codi Dany',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'damageCategory.priority',
//                null,
//                array(
//                    'label'    => 'Prioritat Dany',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'position',
//                null,
//                array(
//                    'label'    => 'Posició',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'radius',
//                null,
//                array(
//                    'label'    => 'Radi',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'distance',
//                null,
//                array(
//                    'label'    => 'Distància',
//                    'editable' => true,
//                )
//            )
//            ->add(
//                'size',
//                null,
//                array(
//                    'label'    => 'Mesura',
//                    'editable' => true,
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
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }
}
