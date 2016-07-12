<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class ObservationAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class ObservationAdmin extends AbstractBaseAdmin
{
    protected $maxPerPage = 50;
    protected $classnameLabel = 'Observacions Danys Pala';
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
        $collection
            ->remove('delete');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(5))
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
                    'label'    => 'Ordre',
                    'required' => false,
                )
            )
            ->add(
                'damageNumber',
                null,
                array(
                    'label'    => 'Número Dany',
                    'required' => true,
                )
            )
            ->add(
                'observations',
                TextareaType::class,
                array(
                    'label'    => 'Observacions',
                    'required' => true,
                    'attr'     => array(
                        'rows' => 5,
                    )
                )
            )
            ->end();
    }
}
