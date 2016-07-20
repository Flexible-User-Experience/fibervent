<?php

namespace AppBundle\Admin;

use AppBundle\Enum\BladeDamageEdgeEnum;
use AppBundle\Enum\BladeDamagePositionEnum;
use AppBundle\Form\Type\ActionButtonFormType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
            ->with('General', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'number',
                null,
                array(
                    'label'    => 'Núm.',
                    'required' => false,
                )
            )
            ->add(
                'damage',
                'sonata_type_model',
                array(
                    'label'      => 'Codi',
                    'btn_add'    => false,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->dr->findAllEnabledSortedByCodeQ(),
                )
            )
            ->add(
                'position',
                ChoiceType::class,
                array(
                    'label'    => 'Posició',
                    'choices'  => BladeDamagePositionEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'radius',
                null,
                array(
                    'label'       => 'Radi (m)',
                    'required'    => true,
                    'sonata_help' => 'm',
                )
            )
            ->add(
                'distance',
                null,
                array(
                    'label'       => 'Distància (cm)',
                    'required'    => true,
                    'sonata_help' => 'cm',
                )
            )
            ->add(
                'edge',
                ChoiceType::class,
                array(
                    'label'    => 'Vora',
                    'choices'  => BladeDamageEdgeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'size',
                null,
                array(
                    'label'       => 'Dimensió (cm)',
                    'required'    => true,
                    'sonata_help' => 'cm',
                )
            )
            ->add(
                'damageCategory',
                'sonata_type_model',
                array(
                    'label'      => 'Cat.',
                    'btn_add'    => false,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->dcr->findEnabledSortedByCategoryQ(),
                )
            )
            ->add(
                'auditWindmillBlade',
                null,
                array(
                    'label'    => 'Pala',
                    'required' => true,
                    'disabled' => false,
                    'attr'     => array(
                        'hidden' => true,
                    ),
                )
            )
            ->end();
        if ($this->id($this->getSubject()) && $this->getRootCode() != $this->getCode()) {
            // is edit mode, disable on new subjects and is children
            $formMapper
                ->add(
                    'fakeAction',
                    ActionButtonFormType::class,
                    array(
                        'text'     => 'Pujar fotos',
                        'url'      => $this->generateObjectUrl('edit', $this->getSubject()),
                        'label'    => 'Accions',
                        'mapped'   => false,
                        'required' => false,
                    )
                )
                ->end();
        } else if ($this->id($this->getSubject())) {
            // is edit mode, disable on new subjects and is children
            $formMapper
                ->with('Fotos', $this->getFormMdSuccessBoxArray(9))
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
                    )
                )
            );
    }
}
