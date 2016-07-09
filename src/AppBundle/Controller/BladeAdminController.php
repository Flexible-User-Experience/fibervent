<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blade;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class BladeAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class BladeAdminController extends AbstractBaseAdminController
{
    /**
     * Redirect the user depend on this choice.
     *
     * @param Blade $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        return new RedirectResponse($this->getRedirectToUrl($request, $object, 'admin_app_windmill_list'));
    }
}
