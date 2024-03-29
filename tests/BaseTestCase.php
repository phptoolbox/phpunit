<?php

/*
 * This file is part of the toolbox package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace toolbox\phpunit\tests;

use toolbox\phpunit\WebTestCase;

/**
 * Description of BaseTestCase
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
abstract class BaseTestCase extends WebTestCase
{    
    public function setUp()
    {
        $this->setBaseUrl(TOOLBOX_PHPUNIT_BASE_TEST_URL);
    }
}

?>
