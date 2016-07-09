<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blade;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class BladeAdminController
 *
 * @category Controller
 * @package  AppBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class BladeAdminController extends Controller
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

        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $router = $this->get('router');
            $url = $router->generate('admin_app_windmill_list');
        }
        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->admin->generateUrl('list');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $params = array();
            if ($this->admin->hasActiveSubClass()) {
                $params['subclass'] = $request->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params);
        }

        if ($this->getRestMethod() === 'DELETE') {
            $url = $this->admin->generateUrl('list');
        }

        if (!$url) {
            foreach (array('edit', 'show') as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->isGranted(strtoupper($route), $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);
                    break;
                }
            }
        }

        if (!$url) {
            $url = $this->admin->generateUrl('list');
        }

        return new RedirectResponse($url);
    }
}
