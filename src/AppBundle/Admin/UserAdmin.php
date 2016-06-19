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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
        '_sort_by'    => 'lastname',
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
        $collection
            ->add('profile')
            ->remove('batch')
            ->remove('delete')
            ->remove('export')
            ->remove('show');
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
                'imageFile',
                'file',
                array(
                    'label'    => 'Foto',
                    'help'     => $this->getImageHelperFormMapperWithThumbnail(),
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
                'plainPassword',
                PasswordType::class,
                array(
                    'label'    => 'Password',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label'    => 'Nom',
                    'required' => false,
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label'    => 'Cognom',
                    'required' => false,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'Telèfon',
                )
            )
            ->end()
            ->with('Rols', array('class' => 'col-md-3'))
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'label'    => 'Rol',
                    'choices'  => UserRolesEnum::getEnumArray(),
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                )
            )
            ->end()
            ->with('Controls', array('class' => 'col-md-3'))
            ->add(
                'locked',
                CheckboxType::class,
                array(
                    'label'    => 'Blocat',
                    'required' => false,
                )
            )
            ->add(
                'enabled',
                CheckboxType::class,
                array(
                    'label'    => 'Actiu',
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
                    'label' => 'Nom Usuari',
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label' => 'Nom',
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label' => 'Cognom',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'Correu electrònic',
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
                'roles',
                'doctrine_orm_choice',
                array(
                    'label'         => 'Rols',
                    'field_type'    => 'choice',
                    'field_options' => array(
                        'choices' => UserRolesEnum::getEnumArray(),
                    ),
                )
            )
            ->add(
                'locked',
                null,
                array(
                    'label' => 'Blocat',
                )
            )
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
                'image',
                null,
                array(
                    'label'    => 'Foto',
                    'template' => '::Admin/Cells/list__cell_image_field.html.twig'
                )
            )
            ->add(
                'firstname',
                null,
                array(
                    'label'    => 'Nom',
                    'editable' => true,
                )
            )
            ->add(
                'lastname',
                null,
                array(
                    'label'    => 'Cognom',
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
                'phone',
                null,
                array(
                    'label'    => 'Telèfon',
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
                'locked',
                null,
                array(
                    'label'    => 'Blocat',
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
                    ),
                )
            );
    }

    /**
     * Get image helper form mapper with thumbnail
     *
     * @return string
     */
    private function getImageHelperFormMapperWithThumbnail()
    {
        $lis = $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
        $vus = $this->getConfigurationPool()->getContainer()->get('vich_uploader.templating.helper.uploader_helper');

        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="' . $lis->getBrowserPath(
                $vus->asset($this->getSubject(), 'imageFile'),
                '480xY'
            ) . '" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '') . '<span style="width:100%;display:block;">Fins a 10MB amb format PNG, JPG or GIF. Amplada mínima 320px.</span>';
    }
}
