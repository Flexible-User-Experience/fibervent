<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class FrontController.
 *
 * @category Controller
 *
 * @author   David Romaní <david@flux.cat>
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="front_homepage")
     *
     * @return RedirectResponse
     */
    public function homepageAction()
    {
        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
