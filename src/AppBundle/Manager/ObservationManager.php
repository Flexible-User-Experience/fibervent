<?php

namespace AppBundle\Manager;

use AppBundle\Entity\BladeDamage;
use AppBundle\Entity\Observation;
use AppBundle\Repository\BladeDamageRepository;

/**
 * Class ObservationManager.
 *
 * @category Manager
 *
 * @author   David Romaní <david@flux.cat>
 */
class ObservationManager
{
    /**
     * @var BladeDamageRepository
     */
    private $bdr;

    /**
     * Methods.
     */

    /**
     * ObservationManager constructor.
     *
     * @param BladeDamageRepository $bdr
     */
    public function __construct(BladeDamageRepository $bdr)
    {
        $this->bdr = $bdr;
    }

    /**
     * Calculate the PDF blade damage number from an observation.
     *
     * @param Observation $observation
     *
     * @return int
     */
    public function getPdfBladeDamageNumber(Observation $observation)
    {
        $damageNumber = 1;
        $bladeDamages = $this->bdr->getItemsOfAuditWindmillBladeSortedByRadius($observation->getAuditWindmillBlade());
        /** @var BladeDamage $bladeDamage */
        foreach ($bladeDamages as $bladeDamage) {
            if ($bladeDamage->getId() == $observation->getDamageNumber()) {
                break;
            }
            ++$damageNumber;
        }

        return $damageNumber;
    }
}
