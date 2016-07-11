<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;

/**
 * Class AuditModelDiagramBridgeService
 *
 * @category Service
 * @package  AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class AuditModelDiagramBridgeService
{
    /**
     * @var Audit
     */
    private $audit;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * CourierService constructor
     *
     * @param Audit $audit
     */
    public function __construct(Audit $audit)
    {
        $this->audit = $audit;
    }
}
