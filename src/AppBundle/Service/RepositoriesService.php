<?php

namespace AppBundle\Service;

use AppBundle\Repository\BladeRepository;
use AppBundle\Repository\CustomerRepository;
use AppBundle\Repository\DamageCategoryRepository;
use AppBundle\Repository\DamageRepository;
use AppBundle\Repository\StateRepository;
use AppBundle\Repository\TurbineRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Repository\WindfarmRepository;
use AppBundle\Repository\WindmillRepository;

/**
 * Class RepositoriesService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class RepositoriesService
{
    /**
     * @var CustomerRepository
     */
    private $cr;

    /**
     * @var UserRepository
     */
    private $ur;

    /**
     * @var WindmillRepository
     */
    private $wmr;

    /**
     * @var WindfarmRepository
     */
    private $wfr;

    /**
     * @var BladeRepository
     */
    private $br;

    /**
     * @var TurbineRepository
     */
    private $tr;

    /**
     * @var StateRepository
     */
    private $sr;

    /**
     * @var DamageRepository
     */
    private $dr;

    /**
     * @var DamageCategoryRepository
     */
    private $dcr;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * RepositoriesService constructor
     *
     * @param CustomerRepository         $cr
     * @param UserRepository             $ur
     * @param WindmillRepository         $wmr
     * @param WindfarmRepository         $wfr
     * @param BladeRepository            $br
     * @param TurbineRepository          $tr
     * @param StateRepository            $sr
     * @param DamageRepository           $dr
     * @param DamageCategoryRepository   $dcr
     */
    public function __construct(CustomerRepository $cr, UserRepository $ur, WindmillRepository $wmr, WindfarmRepository $wfr, BladeRepository $br, TurbineRepository $tr, StateRepository $sr, DamageRepository $dr, DamageCategoryRepository $dcr)
    {
        $this->cr  = $cr;
        $this->ur  = $ur;
        $this->wmr = $wmr;
        $this->wfr = $wfr;
        $this->br  = $br;
        $this->tr  = $tr;
        $this->sr  = $sr;
        $this->dr  = $dr;
        $this->dcr = $dcr;
    }

    /**
     * @return CustomerRepository
     */
    public function getCr()
    {
        return $this->cr;
    }

    /**
     * @return UserRepository
     */
    public function getUr()
    {
        return $this->ur;
    }

    /**
     * @return WindmillRepository
     */
    public function getWmr()
    {
        return $this->wmr;
    }

    /**
     * @return WindfarmRepository
     */
    public function getWfr()
    {
        return $this->wfr;
    }

    /**
     * @return BladeRepository
     */
    public function getBr()
    {
        return $this->br;
    }

    /**
     * @return TurbineRepository
     */
    public function getTr()
    {
        return $this->tr;
    }

    /**
     * @return StateRepository
     */
    public function getSr()
    {
        return $this->sr;
    }

    /**
     * @return DamageRepository
     */
    public function getDr()
    {
        return $this->dr;
    }

    /**
     * @return DamageCategoryRepository
     */
    public function getDcr()
    {
        return $this->dcr;
    }
}
