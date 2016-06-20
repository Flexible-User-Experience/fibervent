<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Audit;
use AppBundle\Entity\AuditWindmillBlade;
use AppBundle\Enum\AuditStatusEnum;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class AuditAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Auditoria';
    protected $baseRoutePattern = 'audits/audit';
    protected $datagridValues = array(
        '_sort_by'    => 'beginDate',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('batch')
            ->add('pdf', $this->getRouterIdParameter() . '/pdf')
            ->add('email', $this->getRouterIdParameter() . '/email');
    }

    /**
     * Override query list to reduce queries amount on list view (apply join strategy)
     *
     * @param string $context context
     *
     * @return QueryBuilder
     */
    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        $query
            ->select($query->getRootAliases()[0] . ', wm, wf, c')
            ->join($query->getRootAliases()[0] . '.windmill', 'wm')
            ->join('wm.windfarm', 'wf')
            ->join('wf.customer', 'c')
//            ->join($query->getRootAliases()[0] . '.operators', 'u')
        ;

        return $query;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', $this->getFormMdSuccessBoxArray(7))
            ->add(
                'windmill',
                'sonata_type_model',
                array(
                    'label'      => 'Aerogenerador',
                    'btn_add'    => false,
                    'btn_delete' => false,
                    'required'   => true,
                    'query'      => $this->wmr->findAllSortedByCustomerWindfarmAndWindmillCodeQ()
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label'    => 'Tipus',
                    'required' => false,
                )
            )
            ->add(
                'tools',
                TextareaType::class,
                array(
                    'label'    => 'Eines',
                    'required' => false,
                    'attr'     => array(
                        'rows' => 8,
                    )
                )
            )
            ->add(
                'observations',
                TextareaType::class,
                array(
                    'label'    => 'Observacions',
                    'required' => false,
                    'attr'     => array(
                        'rows' => 10,
                    )
                )
            )
            ->end()
            ->with('Controls', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'beginDate',
                'sonata_type_date_picker',
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/M/y',
                )
            )
            ->add(
                'endDate',
                'sonata_type_date_picker',
                array(
                    'label'    => 'Data fi',
                    'format'   => 'd/M/y',
                    'required' => false,
                )
            )
            ->add(
                'operators',
                null,
                array(
                    'label'    => 'Tècnics Inspecció',
                    'multiple' => true,
                    'required' => false,
                )
            )
            ->add(
                'status',
                ChoiceType::class,
                array(
                    'label'    => 'Estat',
                    'choices'  => AuditStatusEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjects
            $formMapper
                ->with('Pales auditades', $this->getFormMdSuccessBoxArray(5))
                ->add(
                    'auditWindmillBlades',
                    'sonata_type_collection',
                    array(
                        'label'              => ' ',
                        'required'           => false,
                        'btn_add'            => false,
                        'cascade_validation' => true,
                        'type_options'       => array(
                            'delete' => false,
                        )
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
                'beginDate',
                'doctrine_orm_date',
                array(
                    'label'      => 'Data inici',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'endDate',
                'doctrine_orm_date',
                array(
                    'label'      => 'Data fi',
                    'field_type' => 'sonata_type_date_picker',
                )
            )
            ->add(
                'windmill.windfarm.customer',
                null,
                array(
                    'label' => 'Client',
                )
            )
            ->add(
                'windmill.windfarm',
                null,
                array(
                    'label' => 'Parc Eòlic',
                )
            )
            ->add(
                'windmill',
                null,
                array(
                    'label' => 'Aerogenerador',
                )
            )
            ->add(
                'operators',
                null,
                array(
                    'label' => 'Tècnics Inspecció',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label' => 'Estat',
                ),
                'choice',
                array(
                    'expanded' => false,
                    'multiple' => false,
                    'choices'  => AuditStatusEnum::getEnumArray(),
                )
            )
            ->add(
                'type',
                null,
                array(
                    'label' => 'Tipus',
                )
            )
            ->add(
                'tools',
                null,
                array(
                    'label' => 'Eines',
                )
            )
            ->add(
                'observations',
                null,
                array(
                    'label' => 'Observacions',
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
                'beginDate',
                null,
                array(
                    'label'  => 'Data inici',
                    'format' => 'd/m/Y'
                )
            )
            ->add(
                'windmill.windfarm.customer',
                null,
                array(
                    'label'                            => 'Client',
                    'associated_property'              => 'name',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'customer')),
                )
            )
            ->add(
                'windmill.windfarm',
                null,
                array(
                    'label'                            => 'Parc Eòlic',
                    'associated_property'              => 'name',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'name'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windfarm')),
                )
            )
            ->add(
                'windmill',
                null,
                array(
                    'label'                            => 'Aerogenerador',
                    'associated_property'              => 'code',
                    'sortable'                         => true,
                    'sort_field_mapping'               => array('fieldName' => 'code'),
                    'sort_parent_association_mappings' => array(array('fieldName' => 'windmill')),
                )
            )
            ->add(
                'operators',
                null,
                array(
                    'label' => 'Tècnics Inspecció',
                )
            )
            ->add(
                'status',
                null,
                array(
                    'label'    => 'Estat',
                    'template' => '::Admin/Cells/list__cell_audit_status.html.twig',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'Accions',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'show'   => array('template' => '::Admin/Buttons/list__action_show_button.html.twig'),
                        'pdf'    => array('template' => '::Admin/Buttons/list__action_pdf_button.html.twig'),
                        'email'  => array('template' => '::Admin/Buttons/list__action_email_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    )
                )
            );
    }

    /**
     * @param Audit $object
     */
    public function prePersist($object)
    {
        $windmillBlades = $object->getWindmill()->getWindmillBlades();

        $auditWindmillBlade1 = new AuditWindmillBlade();
        $auditWindmillBlade1
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[0]);
        $auditWindmillBlade2 = new AuditWindmillBlade();
        $auditWindmillBlade2
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[1]);
        $auditWindmillBlade3 = new AuditWindmillBlade();
        $auditWindmillBlade3
            ->setAudit($object)
            ->setWindmillBlade($windmillBlades[2]);
        $object
            ->addAuditWindmillBlade($auditWindmillBlade1)
            ->addAuditWindmillBlade($auditWindmillBlade2)
            ->addAuditWindmillBlade($auditWindmillBlade3);
    }
}
