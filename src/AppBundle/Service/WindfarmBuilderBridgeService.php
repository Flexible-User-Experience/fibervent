<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\User;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Windfarm Builder Bridge Service.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class WindfarmBuilderBridgeService
{
    /**
     * @var Translator
     */
    private $ts;

    /**
     * Methods.
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

    /**
     * @param array|Audit[] $audits
     *
     * @return array of strings transformed
     */
    public function getInvolvedTechniciansInAuditsList($audits)
    {
        $result = array();
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            /** @var User $operator */
            foreach ($audit->getOperators() as $operator) {
                $result[$operator->getId()] = $operator->getFullname();
            }
        }

        return $result;
    }

    /**
     * @param array|Audit[] $audits
     *
     * @return array of strings transformed
     */
    public function getInvolvedAuditTypesInAuditsList($audits)
    {
        $result = array();
        /** @var Audit $audit */
        foreach ($audits as $audit) {
            $result[$audit->getType()] = $this->ts->trans($audit->getTypeStringLocalized());
        }

        return $result;
    }

    /**
     * @param array $dates
     *
     * @return array of strings transformed
     */
    public function getInvolvedAuditDatesInAuditsList($dates)
    {
        $result = array();
        if (count($dates) > 0) {
            $result[] = $dates['begin']->format('d-m-Y');
            $result[] = $dates['end']->format('d-m-Y');
        }

        return $result;
    }
}
