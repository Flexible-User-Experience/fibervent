<?php

namespace AppBundle\Manager;

use AppBundle\Model\AjaxResponse;
use AppBundle\Repository\AuditRepository;

/**
 * Class CustomerAjaxResponseManager.
 *
 * @category Manager
 *
 * @author   David Romaní <david@flux.cat>
 */
class CustomerAjaxResponseManager
{
    /**
     * @var AuditRepository
     */
    private $ar;

    /**
     * Methods.
     */

    /**
     * CustomerAjaxResponseManager constructor.
     *
     * @param AuditRepository $ar
     */
    public function __construct(AuditRepository $ar)
    {
        $this->ar = $ar;
    }

    /**
     * @param int $cid
     *
     * @return false|string
     */
    public function getAuditsAjaxResponseFromCustomerId($cid)
    {
        $ajaxResponse = new AjaxResponse();
        $ajaxResponse->setData($this->ar->getAuditsFromCustomerId($cid));

        return $ajaxResponse->getJsonEncodedResult();
    }
}
