<?php

namespace AppBundle\Admin;

use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Form\Type\ActionButtonFormType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class AuditWindmillBladeAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class AuditWindmillBladeAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'admin.auditwindmillblade.title';
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
                ->with('admin.common.general', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'audit',
                    null,
                    array(
                        'label'    => 'admin.auditwindmillblade.audit',
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
                        'label'    => 'admin.auditwindmillblade.windmillblade',
                        'required' => true,
                        'disabled' => true,
                    )
                )
                ->end()
                ->with('admin.auditwindmillblade.damages', $this->getFormMdSuccessBoxArray(3))
                ->add(
                    'fakeAction',
                    ActionButtonFormType::class,
                    array(
                        'text'     => 'Editar daños',
                        'url'      => $this->generateObjectUrl('edit', $this->getSubject()),
                        'label'    => 'admin.auditwindmillblade.actions',
                        'mapped'   => false,
                        'required' => false,
                    )
                )
                ->end();
        } else {
            // else is normal admin view
            /** @var AuditWindmillBlade $awb */
            $awb = $this->getSubject();
            $order = $awb->getWindmillBlade() ? $awb->getWindmillBlade()->getOrder() : '';
            $code = $awb->getWindmillBlade() && empty($awb->getWindmillBlade()->getCode()) ? 'admin.windmillblade.sn' . $awb->getWindmillBlade()->getCode() . ')' : '';
            $formMapper
                ->with('Situación y descripción de los daños · Pala ' . $order . ' ' . $code, $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'bladeDamages',
                    'sonata_type_collection',
                    array(
                        'label'              => 'admin.auditwindmillblade.damages',
                        'required'           => true,
                        'cascade_validation' => true,
                        'error_bubbling'     => true,
                    ),
                    array(
                        'edit'     => 'inline',
                        'inline'   => 'table',
                    )
                )
                ->end()
                ->with('Observaciones · Pala ' . $order . ' ' . $code, $this->getFormMdSuccessBoxArray(8))
                ->add(
                    'observations',
                    'sonata_type_collection',
                    array(
                        'label'              => 'admin.audit.observations',
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
                ->end()
                ->with('Fotos generals · Pala ' . $order . ' ' . $code, $this->getFormMdSuccessBoxArray(4))
                ->add(
                    'bladePhotos',
                    'sonata_type_collection',
                    array(
                        'label'              => 'admin.auditwindmillblade.photos',
                        'required'           => true,
                        'cascade_validation' => true,
                        'error_bubbling'     => true,
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
                    'label'    => 'admin.auditwindmillblade.audit',
                    'editable' => true,
                )
            )
            ->add(
                'windmillBlade',
                null,
                array(
                    'label'    => 'admin.auditwindmillblade.windmillblade',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'admin.common.action',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }
}
