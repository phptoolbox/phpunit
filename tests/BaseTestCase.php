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
    protected $baseUrl;

    public function setUp()
    {
        $this->baseUrl = TOOLBOX_PHPUNIT_TESTS_URL;
    }

    public function url($url=null)
    {
        if(false===  strpos($url, $this->baseUrl)){
            $url = !is_null($url) ? $this->baseUrl.'/'.$url:$url;
        }
        return parent::url($url);
    }
}

?>
