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
use toolbox\phpunit\element\Select;

/**
 * Description of WebTestCase
 *
 * @method \toolbox\phpunit\Crawler filter(string $selector) Filters the list of nodes with a CSS selector.
 * @author Anthonius Munthi <me@itstoni.com>
 */
class WebTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var toolbox\phpunit\Client
     */
    private $client;

    private $method = "GET";

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

    /**
     * @return toolbox\phpunit\Crawler
     */
    protected function getCrawler()
    {
        return $this->getClient()->request($this->method);
    }

    /**
     * @return text
     */
    public function title()
    {
        return $this->by('title')->text();
    }

    /**
     *
     * @param   type $selector
     * @param   type $strategy
     * @return  \toolbox\phpunit\Crawler
     */
    public function by($selector,$strategy=null)
    {
        return $this->getCrawler()->filter($selector);
    }

    /**
     * @param   string  $id
     * @return  \toolbox\phpunit\Crawler
     */
    public function byId($id)
    {
        if(false===  strpos($id, '#')){
            $id = '#'.$id;
        }
        return $this->getCrawler()->filter($id);
    }

    /**
     * @param  string  $name
     * @return \toolbox\phpunit\Crawler
     */
    public function byName($name)
    {
        return $this->getCrawler()->filter('[name='.$name.']');
    }

    /**
     * @param  string  $class
     * @return \toolbox\phpunit\Crawler
     */
    public function byClass($class)
    {
        $class = (1!==strpos($class,'.')) ? '.'.$class:$class;
        return $this->getCrawler()->filter($class);
    }

    /**
     * @param   string      $selector
     * @return  \toolbox\phpunit\element\Select
     */
    public function select($selector)
    {
        $crawler = $this->getCrawler()->filter($selector);
        return new Select($crawler);
    }

    public function __call($name, $arguments)
    {
        if(method_exists($this->getCrawler(),$name)){
            return call_user_func_array(array($this->getCrawler(),$name), $arguments);
        }
        $this->markTestIncomplete("Method not exists: ".__CLASS__.'::'.$name);
    }
}

?>
