<?php

namespace AppBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract class AuditAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
abstract class AbstractBaseAdminController extends Controller
{
    /**
     * @param Request|null $request
     *
     * @return Request
     */
    protected function resolveRequest(Request $request = null)
    {
        if (null === $request) {
            return $this->getRequest();
        }

        return $request;
    }
}
