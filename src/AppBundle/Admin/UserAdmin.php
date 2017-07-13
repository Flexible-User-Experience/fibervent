<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Customer;
use AppBundle\Enum\UserRolesEnum;
use AppBundle\Enum\WindfarmLanguageEnum;
use Sonata\UserBundle\Admin\Model\UserAdmin as ParentUserAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Class UserAdmin.
 *
 * @category Admin
 *
 * @author   David Romaní <david@flux.cat>
 */
class UserAdmin extends ParentUserAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $perPageOptions = array(25, 50, 100, 200);

    /**
     * @var int
     */
    protected $maxPerPage = 25;

    protected $classnameLabel = 'admin.user.title';
    protected $baseRoutePattern = 'users';
    protected $datagridValues = array(
        '_sort_by' => 'lastname',
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
     * Available routes.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('profile')
            ->remove('batch')
            ->remove('delete')
            ->remove('export')
            ->remove('show');
    }

    /**
     * Remove batch action list view first column.
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
        /* @var object $formMapper */
        $formMapper
            ->with('admin.common.general', array('class' => 'col-md-6'))
            ->add(
                'imageFile',
                'file',
                array(
                    'label' => 'admin.bladephoto.imagefile',
                    'help' => $this->getImageHelperFormMapperWithThumbnail(),
                    'required' => false,
                )
            )
            ->add(
                'username',
                null,
                array(
                    'label' => 'admin.user.username',
                    'help' => 'admin.user.username_help',
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'admin.customer.email',
                )
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                array(
                    'label' => 'admin.user.password',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label' => 'admin.user.firstname',
                    'required' => false,
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label' => 'admin.user.lastname',
                    'required' => false,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'admin.customer.phone',
                )
            )
            ->end()
            ->with('admin.user.roles', array('class' => 'col-md-3'))
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'label' => 'admin.user.rol',
                    'choices' => UserRolesEnum::getEnumArray(),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                )
            )
            ->end()
            ->with('admin.common.controls', array('class' => 'col-md-3'))
            ->add(
                'customer',
                EntityType::class,
                array(
                    'label' => 'admin.user.customer',
                    'class' => Customer::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.customer_repository')->findAllSortedByNameQB(),
                )
            )
            ->add(
                'language',
                ChoiceType::class,
                array(
                    'label' => 'admin.user.language',
                    'choices' => WindfarmLanguageEnum::getEnumArrayString(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                )
            )
            ->add(
                'locked',
                CheckboxType::class,
                array(
                    'label' => 'admin.user.locked',
                    'required' => false,
                )
            )
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label' => 'admin.common.enabled',
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
                    'label' => 'admin.user.username',
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label' => 'admin.user.firstname',
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label' => 'admin.user.lastname',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'admin.customer.email',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'admin.customer.phone',
                )
            )
            ->add(
                'roles',
                'doctrine_orm_choice',
                array(
                    'label' => 'admin.user.roles',
                    'field_type' => 'choice',
                    'field_options' => array(
                        'choices' => UserRolesEnum::getEnumArray(),
                    ),
                )
            )
            ->add(
                'locked',
                null,
                array(
                    'label' => 'admin.user.locked',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'admin.common.enabled',
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
                'image',
                null,
                array(
                    'label' => 'admin.bladephoto.imagefile',
                    'template' => '::Admin/Cells/list__cell_image_field.html.twig',
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label' => 'admin.user.firstname',
                    'editable' => true,
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label' => 'admin.user.lastname',
                    'editable' => true,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'admin.customer.email',
                    'editable' => true,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'admin.customer.phone',
                    'editable' => true,
                )
            )
            ->add(
                'roles',
                null,
                array(
                    'label' => 'admin.user.roles',
                    'template' => '::Admin/Cells/list__cell_user_roles.html.twig',
                )
            )
            ->add(
                'locked',
                null,
                array(
                    'label' => 'admin.user.locked',
                    'editable' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'admin.common.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'admin.common.action',
                    'actions' => array(
                        'edit' => array('template' => '::Admin/Buttons/list__action_edit_button.html.twig'),
                    ),
                )
            );
    }

    /**
     * Get image helper form mapper with thumbnail.
     *
     * @return string
     */
    private function getImageHelperFormMapperWithThumbnail()
    {
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');

        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="'.$lis->getBrowserPath(
                $vus->asset($this->getSubject(), 'imageFile'),
                '480xY'
            ).'" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '').'<span style="width:100%;display:block;">'.$this->trans('admin.photo.help', ['width' => 320]).'</span>';
    }
}
