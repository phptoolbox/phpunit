<?php

/*
 * This file is part of the toolbox package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace toolbox\phpunit;

use PHPUnit_Framework_TestCase;
use toolbox\phpunit\Client;

/**
 * Description of WebTestCase
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class WebTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Selenium compatibility only
     */
    const VERSION = '1.0.0';

    /**
     * @var toolbox\phpunit\Client
     */
    private $client;

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->parameters = array(
            'host' => 'localhost',
            'port' => 4444,
            'timeout' => 10
        );
    }

    public function url($url=null)
    {
        if(!is_null($url)){
            $this->getClient()->setUrl($url);
        }
        return $this->getClient()->getUrl();
    }

    /**
     * @return toolbox\phpunit\Client
     */
    protected function getClient()
    {
        if(!is_object($this->client)){
            $this->client = new Client();
        }
        return $this->client;
    }

    public function title()
    {
        $this->getRequest();
    }

    public function __call($name, $arguments)
    {
        if(false===  method_exists($this, $name)){
            $this->markTestIncomplete("Method not exists: ".__CLASS__.'::'.$name);
        }
    }
}

?>
