<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Turbine;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class TurbineAdminController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class TurbineAdminController extends AbstractBaseAdminController
{
    /**
     * Redirect the user depend on this choice.
     *
     * @param Turbine $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        return new RedirectResponse($this->getRedirectToUrl($request, $object, 'admin_app_windmill_list'));
    }
}
