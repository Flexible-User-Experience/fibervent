<?php

namespace AppBundle\Admin;

use AppBundle\Enum\UserRolesEnum;
use Sonata\UserBundle\Admin\Model\UserAdmin as ParentUserAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Class UserAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
class UserAdmin extends ParentUserAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    protected $classnameLabel = 'User';
    protected $baseRoutePattern = 'users';
    protected $datagridValues = array(
        '_sort_by'    => 'username',
        '_sort_order' => 'asc',
    );

    public function __construct($code, $class, $baseControllerName, $userManager)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->userManager = $userManager;
    }

    /**
     * Available routes
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('batch');
        $collection->remove('export');
        $collection->remove('show');
    }

    /**
     * Remove batch action list view first column
     *
     * @return array
     */
    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var object $formMapper */
        $formMapper
            ->with('backend.admin.general', array('class' => 'col-md-6'))
            ->add(
                'firstname',
                null,
                array(
                    'label'    => 'backend.admin.user.firstname',
                    'required' => false,
                )
            )
            ->add(
                'username',
                null,
                array(
                    'label' => 'backend.admin.user.username',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.user.email',
                )
            )
            ->add(
                'plainPassword',
                'text',
                array(
                    'label'    => 'backend.admin.user.plain_password',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                )
            )
            ->end()
            ->with('backend.admin.controls', array('class' => 'col-md-6'))
            ->add(
                'roles',
                'choice',
                array(
                    'label'    => 'backend.admin.user.roles',
                    'choices'  => UserRolesEnum::getEnumArray(),
                    'multiple' => true,
                    'expanded' => true
                )
            )
            ->add(
                'enabled',
                'checkbox',
                array(
                    'label'    => 'backend.admin.enabled',
                    'required' => false,
                )
            )
            ->end();
    }

    /**
     * @param DatagridMapper $filterMapper
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add(
                'username',
                null,
                array(
                    'label' => 'backend.admin.user.username',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.user.email',
                )
            )
//            ->add(
//                'roles',
//                'doctrine_orm_string',
//                array(
//                    'choice',
//                    array('choices' => UserRolesEnum::getEnumArray()),
//                )
//            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
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
                'username',
                null,
                array(
                    'label'    => 'backend.admin.user.username',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label'    => 'backend.admin.user.email',
                    'editable' => true,
                )
            )
            ->add(
                'roles',
                null,
                array(
                    'label'    => 'backend.admin.user.roles',
                    'template' => '::Admin/Cells/list__cell_user_roles.html.twig',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label'    => 'backend.admin.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label'   => 'backend.admin.actions',
                    'actions' => array(
                        'edit'   => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => '::Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                )
            );
    }
}
