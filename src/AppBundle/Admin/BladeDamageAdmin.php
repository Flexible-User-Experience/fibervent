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
                'damage.code',
                null,
                array(
                    'label' => 'Codi Dany',
                )
            )
            ->add(
                'damageCategory.priority',
                null,
                array(
                    'label' => 'Prioritat Dany',
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
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label'    => 'Distància',
                    'required' => true,
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label'    => 'Mesura',
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
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'damage.code',
                null,
                array(
                    'label' => 'Codi Dany',
                )
            )
            ->add(
                'damageCategory.priority',
                null,
                array(
                    'label' => 'Prioritat Dany',
                )
            )
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
            ->add(
                'damage.code',
                null,
                array(
                    'label'    => 'Codi Dany',
                    'editable' => true,
                )
            )
            ->add(
                'damageCategory.priority',
                null,
                array(
                    'label'    => 'Prioritat Dany',
                    'editable' => true,
                )
            )
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
