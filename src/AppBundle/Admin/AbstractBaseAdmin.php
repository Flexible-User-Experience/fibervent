<?php

namespace AppBundle\Admin;

use AppBundle\Repository\BladeRepository;
use AppBundle\Repository\StateRepository;
use AppBundle\Repository\TurbineRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Repository\WindfarmRepository;
use AppBundle\Repository\WindmillRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class BaseAdmin
 *
 * @category Admin
 * @package  AppBundle\Admin
 * @author   David Romaní <david@flux.cat>
 */
abstract class AbstractBaseAdmin extends AbstractAdmin
{
    /**
     * @var WindmillRepository
     */
    protected $wmr;

    /**
     * @var WindfarmRepository
     */
    protected $wfr;

    /**
     * @var BladeRepository
     */
    protected $br;

    /**
     * @var TurbineRepository
     */
    protected $tr;

    /**
     * @var StateRepository
     */
    protected $sr;

    /**
     * @var UserRepository
     */
    protected $ur;

    /**
     * @var UploaderHelper
     */
    protected $vus;

    /**
     * @var CacheManager
     */
    protected $lis;

    /**
     * @param string             $code
     * @param string             $class
     * @param string             $baseControllerName
     * @param UserRepository     $ur
     * @param WindmillRepository $wmr
     * @param WindfarmRepository $wfr
     * @param BladeRepository    $br
     * @param TurbineRepository  $tr
     * @param StateRepository    $sr
     * @param UploaderHelper     $vus
     * @param CacheManager       $lis
     */
    public function __construct($code, $class, $baseControllerName, UserRepository $ur, WindmillRepository $wmr, WindfarmRepository $wfr, BladeRepository $br, TurbineRepository $tr, StateRepository $sr, UploaderHelper $vus, CacheManager $lis)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->ur  = $ur;
        $this->wmr = $wmr;
        $this->wfr = $wfr;
        $this->br  = $br;
        $this->tr  = $tr;
        $this->sr  = $sr;
        $this->vus = $vus;
        $this->lis = $lis;
    }

    /**
     * @var array
     */
    protected $perPageOptions = array(25, 50, 100, 200);

    /**
     * @var int
     */
    protected $maxPerPage = 25;

    /**
     * Configure route collection
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('show')
            ->remove('batch');
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
     * Get export formats
     *
     * @return array
     */
    public function getExportFormats()
    {
        return array(
            'csv',
            'xls',
        );
    }

    /**
     * @param string $bootstrapGrid
     * @param string $bootstrapSize
     * @param string $boxClass
     *
     * @return array
     */
    protected function getDefaultFormBoxArray($bootstrapGrid = 'md', $bootstrapSize = '6', $boxClass = 'primary')
    {
        return array(
            'class'     => 'col-' . $bootstrapGrid . '-' . $bootstrapSize,
            'box_class' => 'box box-' . $boxClass,
        );
    }

    /**
     * @param string $bootstrapColSize
     *
     * @return array
     */
    protected function getFormMdSuccessBoxArray($bootstrapColSize = '6')
    {
        return $this->getDefaultFormBoxArray('md', $bootstrapColSize, 'success');
    }

    /**
     * Get image helper form mapper with thumbnail
     *
     * @return string
     */
    protected function getImageHelperFormMapperWithThumbnail()
    {
        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="' . $this->lis->getBrowserPath(
                $this->vus->asset($this->getSubject(), 'imageFile'),
                '480xY'
            ) . '" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '') . '<span style="width:100%;display:block;">Fins a 10MB amb format PNG, JPG or GIF. Amplada mínima 1200px.</span>';
    }
}
