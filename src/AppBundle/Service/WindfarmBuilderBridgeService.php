<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\Turbine;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Windfarm Builder Bridge Service.
 *
 * @category Service
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmBuilderBridgeService
{
    /**
     * @var Translator
     */
    private $ts;

    /**
     * Methods
     */

    /**
     * WindfarmBuilderBridgeService constructor.
     *
     * @param TranslatorInterface $ts
     */
    public function __construct(TranslatorInterface $ts)
    {
        $this->ts = $ts;
    }

    /**
     * @param array|Audit[] $audits
     *
     * @return array of strings transformed
     */
    public function getInvolvedTurbinesInAuditsList($audits)
    {
        $result = array();
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            $result[$audit->getWindmill()->getTurbine()->getId()] = $audit->getWindmill()->getTurbine()->pdfToString();
        }

        return $result;
    }

    /**
     * @param array|Audit[] $audits
     *
     * @return array of strings transformed
     */
    public function getInvolvedTurbineModelsInAuditsList($audits)
    {
        $result = array();
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            $result[$audit->getWindmill()->getTurbine()->getId()] = $this->ts->trans('pdf.cover.6_turbine_size_value', array(
                '%height%' => $audit->getWindmill()->getTurbine()->getTowerHeight(),
                '%diameter%' => $audit->getWindmill()->getTurbine()->getRotorDiameter(),
                '%length%' => $audit->getWindmill()->getBladeType()->getLength(),
            ));
        }

        return $result;
    }

    /**
     * @param array|Audit[] $audits
     *
     * @return array of strings transformed
     */
    public function getInvolvedBladesInAuditsList($audits)
    {
        $result = array();
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            $result[$audit->getWindmill()->getBladeType()->getId()] = $audit->getWindmill()->getBladeType()->__toString();
        }

        return $result;
    }
}
