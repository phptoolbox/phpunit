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

use Goutte\Client;
use toolbox\phpunit\Crawler;

/**
 * Description of Client
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class Browser extends Client
{
    public function open($uri = null, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        try{
            return $this->request('GET', $uri, $parameters, $files, $server, $content, $changeHistory);
        }catch(\Exception $e){
            throw $e;
        }
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
     * @return \toolbox\phpunit\Crawler
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
