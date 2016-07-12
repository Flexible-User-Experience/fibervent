<?php

namespace AppBundle\Admin;

use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Form\Type\ActionButtonFormType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
        if ($this->id($this->getSubject()) && $this->getRootCode() != $this->getCode()) {
            // is edit mode, disable on new subjects and is children
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
//                ->add(
//                    'observations',
//                    'sonata_type_collection',
//                    array(
//                        'label'              => 'Observacions',
//                        'required'           => true,
//                        'cascade_validation' => true,
//                        'error_bubbling'     => true,
//                    ),
//                    array(
//                        'edit'     => 'inline',
//                        'inline'   => 'table',
//                        'sortable' => 'number',
//                    )
//                )
                ->end()
                ->with('Danys', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'fakeAction',
                    ActionButtonFormType::class,
                    array(
                        'text'     => 'Editar danys',
                        'url'      => $this->generateObjectUrl('edit', $this->getSubject()),
                        'label'    => 'Accions',
                        'mapped'   => false,
                        'required' => false,
                    )
                )
                ->end();
        } else {
            // else is normal admin view
            /** @var AuditWindmillBlade $awb */
            $awb = $this->getSubject();
            $text = $awb->getWindmillBlade() ? $awb->getWindmillBlade()->getCode() : '';
            $formMapper
                ->with('Situació i descripció dels danys · Pala ' . $text, $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'bladeDamages',
                    'sonata_type_collection',
                    array(
                        'label'              => 'Danys',
                        'required'           => true,
                        'cascade_validation' => true,
                        'error_bubbling'     => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                        'sortable' => 'number',
                    )
                )
                ->end()
                ->with('Observacions · Pala ' . $text, $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'observations',
                    'sonata_type_collection',
                    array(
                        'label'              => 'Observacions',
                        'required'           => true,
                        'cascade_validation' => true,
                        'error_bubbling'     => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                        'sortable' => 'position',
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
