<?php

namespace AppBundle\Admin;

use AppBundle\Enum\UserRolesEnum;
use Sonata\UserBundle\Admin\Model\UserAdmin as ParentUserAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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

    protected $classnameLabel = 'Usuari';
    protected $baseRoutePattern = 'users';
    protected $datagridValues = array(
        '_sort_by'    => 'username',
        '_sort_order' => 'asc',
    );

    /**
     * UserAdmin constructor.
     *
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param        $userManager
     */
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
            ->with('General', array('class' => 'col-md-6'))
            ->add(
                'firstname',
                null,
                array(
                    'label'    => 'Nom',
                    'required' => false,
                )
            )
            ->add(
                'username',
                null,
                array(
                    'label' => 'Nom Usuari',
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'Correu Electrònic',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon',
                )
            )
            ->add(
                'plainPassword',
                TextType::class,
                array(
                    'label'    => 'Password',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                )
            )
            ->end()
            ->with('Controls', array('class' => 'col-md-6'))
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label'    => 'Actiu',
                    'required' => false,
                )
            )
            ->add(
                'locked',
                CheckboxType::class,
                array(
                    'label'    => 'Blocat',
                    'required' => false,
                )
            )
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'label'    => 'Rols',
                    'choices'  => UserRolesEnum::getEnumArray(),
                    'multiple' => true,
                    'expanded' => true
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
                    'label' => 'Nom Usuari',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'Correu electrònic',
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
                    'label' => 'Actiu',
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
                    'label'    => 'Nom Usuari',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label'    => 'Correu Electrònic',
                    'editable' => true,
                )
            )
            ->add(
                'roles',
                null,
                array(
                    'label'    => 'Rols',
                    'template' => '::Admin/Cells/list__cell_user_roles.html.twig',
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
                    ),
                )
            );
    }
}
