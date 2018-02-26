<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class ObservationAdmin
 *
 * @category Admin
 * @author   David Romaní <david@flux.cat>
 */
class ObservationAdmin extends AbstractBaseAdmin
{
    protected $maxPerPage = 50;
    protected $classnameLabel = 'admin.observation.title';
    protected $baseRoutePattern = 'audits/observation';
    protected $datagridValues = array(
        '_sort_by'    => 'position',
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
            ->with('admin.common.general', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'auditWindmillBlade',
                null,
                array(
                    'attr' => array(
                        'hidden' => true,
                    ),
                )
            )
            ->add(
                'position',
                null,
                array(
                    'label'    => 'admin.observation.position',
                    'required' => false,
                )
            )
            ->add(
                'damageNumber',
                null,
                array(
                    'label'    => 'admin.observation.damage_number',
                    'required' => true,
                )
            )
            ->add(
                'observations',
                TextareaType::class,
                array(
                    'label'    => 'admin.audit.observations',
                    'required' => true,
                    'attr'     => array(
                        'rows' => 5,
                    )
                )
            )
            ->end();
    }
}
