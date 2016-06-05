<?php

namespace AppBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class abstract base test
 *
 * @category Test
 * @package  AppBundle\Tests
 * @author   David Romaní <david@flux.cat>
 */
abstract class AbstractBaseTest extends WebTestCase
{
    /**
     * Set up test
     */
    public function setUp()
    {
        $this->runCommand('hautelook_alice:doctrine:fixtures:load');
    }
}
