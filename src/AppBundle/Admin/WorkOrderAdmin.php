<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class WorkOrderAdmin extends AbstractAdmin
{
    protected $classnameLabel = 'admin.workorder.title';
    protected $baseRoutePattern = 'workorders/workorder';

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('projectNumber')
            ->add('isFromAudit')
            ->add('certifyingCompanyName')
            ->add('certifyingCompanyContactPerson')
            ->add('certifyingCompanyPhone')
            ->add('certifyingCompanyEmail')
            ->add('repairAccessTypes')
            ->add('id')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('enabled')
            ->add('removedAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('projectNumber')
            ->add('isFromAudit')
            ->add('certifyingCompanyName')
            ->add('certifyingCompanyContactPerson')
            ->add('certifyingCompanyPhone')
            ->add('certifyingCompanyEmail')
            ->add('repairAccessTypes')
            ->add('id')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('enabled')
            ->add('removedAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('projectNumber')
            ->add('isFromAudit')
            ->add('certifyingCompanyName')
            ->add('certifyingCompanyContactPerson')
            ->add('certifyingCompanyPhone')
            ->add('certifyingCompanyEmail')
            ->add('repairAccessTypes')
            ->add('id')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('enabled')
            ->add('removedAt')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('projectNumber')
            ->add('isFromAudit')
            ->add('certifyingCompanyName')
            ->add('certifyingCompanyContactPerson')
            ->add('certifyingCompanyPhone')
            ->add('certifyingCompanyEmail')
            ->add('repairAccessTypes')
            ->add('id')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('enabled')
            ->add('removedAt')
        ;
    }
}
