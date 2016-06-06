<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AbstractBaseTest;

/**
 * Class BackendControllerTest
 *
 * @category Test
 * @package  AppBundle\Tests\Controller
 * @author   Anton Serra <aserratorta@gmail.com.cat>
 */
class BackendControllerTest extends AbstractBaseTest
{
    /**
     * Test admin login request is successful
     */
    public function testAdminLoginPageIsSuccessful()
    {
        $client = $this->createClient();           // anonymous user
        $client->request('GET', '/admin/login');

        $this->assertStatusCode(200, $client);
    }

    /**
     * Test HTTP request is successful
     *
     * @dataProvider provideSuccessfulUrls
     * @param string $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->makeClient(true);         // authenticated user
        $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
    }

    /**
     * Successful Urls provider
     *
     * @return array
     */
    public function provideSuccessfulUrls()
    {
        return array(
            array('/admin/dashboard'),
            array('/admin/customers/customer/list'),
            array('/admin/customers/customer/create'),
            array('/admin/customers/customer/1/delete'),
            array('/admin/customers/customer/1/edit'),
            array('/admin/customers/country/list'),
            array('/admin/customers/country/create'),
            array('/admin/customers/country/1/delete'),
            array('/admin/customers/country/1/edit'),
            array('/admin/customers/state/list'),
            array('/admin/customers/state/create'),
            array('/admin/customers/state/1/delete'),
            array('/admin/customers/state/1/edit'),
            array('/admin/windfarms/windfarm/list'),
            array('/admin/windfarms/windfarm/create'),
            array('/admin/windfarms/windfarm/1/delete'),
            array('/admin/windfarms/windfarm/1/edit'),
            array('/admin/windfarms/windmill/list'),
            array('/admin/windfarms/windmill/create'),
            array('/admin/windfarms/windmill/1/delete'),
            array('/admin/windfarms/windmill/1/edit'),
            array('/admin/users/list'),
            array('/admin/users/create'),
            array('/admin/users/1/edit'),
            array('/admin/users/1/delete'),
        );
    }

    /**
     * Test HTTP request is not found
     *
     * @dataProvider provideNotFoundUrls
     * @param string $url
     */
    public function testAdminPagesAreNotFound($url)
    {
        $client = $this->makeClient(true);         // authenticated user
        $client->request('GET', $url);

        $this->assertStatusCode(404, $client);
    }

    /**
     * Not found Urls provider
     *
     * @return array
     */
    public function provideNotFoundUrls()
    {
        return array(
            array('/admin/customers/customer/batch'),
            array('/admin/customers/country/batch'),
            array('/admin/customers/state/batch'),
            array('/admin/windfarms/windfarm/batch'),
            array('/admin/users/show'),
            array('/admin/users/batch'),
        );
    }
}
