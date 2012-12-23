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

use toolbox\phpunit\Browser;

/**
 * Description of WebTestCase
 *
 * @method \toolbox\phpunit\Crawler filter(string $selector) Filters the list of nodes with a CSS selector.
 * @method \toolbox\phpunit\Crawler filterXPath(string $selector) Filters the list of nodes with an XPath expression.
 * @method \toolbox\phpunit\Crawler byId(string $id) Filter the list of node by Element id
 * @method \toolbox\phpunit\Crawler byCssClass(string $class) Filter the list of node by CSS Class
 * @method \toolbox\phpunit\Crawler byName(string $name,string $element) Filter the list of node by their name.
 * @method boolean hasElement(string $selector) Check if response content has element by the given selector
 * @author Anthonius Munthi <me@itstoni.com>
 */
class WebTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var toolbox\phpunit\Browser
     */
    private $browser;

    private $baseUrl = null;

    /**
     * @var \toolbox\phpunit\Crawler
     */
    private $crawler;

    /**
     * @return \toolbox\phpunit\Browser
     */
    protected function getBrowser()
    {
        if(!is_object($this->browser)){
            $this->browser = new Browser();
        }
        return $this->browser;
    }

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    public function open($uri = null, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        if(false===strpos($uri, $this->baseUrl) && false===strpos($uri,'http://')){
            $sep = strpos($uri, '/')===0 ? '':'/';
            $uri = $this->baseUrl.$sep.$uri;
        }
        $this->crawler = $this->getBrowser()->open($uri, $parameters, $files, $server, $content, $changeHistory);
    }

    public function statusCode()
    {
        return $this->getBrowser()->getResponse()->getStatus();
    }

    public function title()
    {
        return $this->crawler->filter('title')->text();
    }

    /**
     * @return \toolbox\phpunit\Crawler
     */
    public function body()
    {
        return $this->crawler->filter('body');
    }

    public function assertTitle($expected,$message = '', $delta = 0, $maxDepth = 10, $canonicalize = FALSE, $ignoreCase = FALSE)
    {
        $this->assertEquals($expected, $this->title(), $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    public function assertBodyContains($expected, $message = '', $ignoreCase = FALSE, $checkForObjectIdentity = TRUE)
    {
        $haystack = $this->body()->text();
        $this->assertContains($expected, $haystack, $message, $ignoreCase, $checkForObjectIdentity);
    }

    public function assertStatusCode($expected,$message="")
    {
        $this->assertEquals($expected,$this->getBrowser()->getResponse()->getStatus(),$message);
    }

    public function assertElementPresent($selector,$message="")
    {
        $this->assertTrue($this->hasElement($selector),$message);
    }

    public function __call($name, $arguments)
    {
        $crawler = $this->crawler;
        if(is_object($crawler) && method_exists($crawler, $name)){
            return call_user_func_array(array($crawler,$name), $arguments);
        }
    }
}

?>
