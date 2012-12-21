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

use Goutte\Client as BaseClient;
use toolbox\phpunit\Crawler;

/**
 * Description of Client
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class Client extends BaseClient
{
    private $url;

    /**
     *
     * @param   string  $url
     * @return  \toolbox\phpunit\Client
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Calls a URI.
     *
     * @param string  $method        The request method
     * @param string  $uri           The URI to fetch
     * @param array   $parameters    The Request parameters
     * @param array   $files         The files
     * @param array   $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string  $content       The raw body data
     * @param Boolean $changeHistory Whether to update the history or not (only used internally for back(), forward(), and reload())
     *
     * @return \toolbox\phpunit\Crawler
     *
     * @api
     */
    public function request($method, $uri=null, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        if(is_null($uri) && is_null($this->url)){
            throw new BadMethodCallException('phpunit.uri_not_defined');
        }
        $uri = is_null($uri) ? $this->url:$uri;
        return parent::request($method, $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Creates a crawler.
     *
     * This method returns null if the DomCrawler component is not available.
     *
     * @param string $uri     A uri
     * @param string $content Content for the crawler to use
     * @param string $type    Content type
     *
     * @return toolbox\phpunit\Crawler
     */
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        if (!class_exists('Symfony\Component\DomCrawler\Crawler')) {
            return null;
        }

        $crawler = new Crawler(null, $uri);
        $crawler->addContent($content, $type);

        return $crawler;
    }

    
}

?>
