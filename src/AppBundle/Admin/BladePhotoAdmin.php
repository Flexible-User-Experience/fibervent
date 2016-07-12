<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class BladePhotoAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class BladePhotoAdmin extends AbstractBaseAdmin
{
    protected $maxPerPage = 50;
    protected $classnameLabel = 'Foto Pala';
    protected $baseRoutePattern = 'audits/blade-photo';
    protected $datagridValues = array(
        '_sort_by'    => 'imageName',
        '_sort_order' => 'asc',
    );

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
                'imageFile',
                'file',
                array(
                    'label'       => 'Foto',
                    'help'        => $this->getImageHelperFormMapperWithThumbnail(),
                    'sonata_help' => $this->getImageHelperFormMapperWithThumbnail(),
                    'required'    => false,
                )
            )
            ->end();
    }
}
