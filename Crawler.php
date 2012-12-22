<?php

/*
 * This file is part of the toolbox-phpunit package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace toolbox\phpunit;

use Symfony\Component\DomCrawler\Crawler as BaseCrawler;

/**
 * Description of Crawler
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class Crawler extends BaseCrawler
{
    public function filter($selector)
    {
        try{
            return parent::filter($selector);
        }catch(\Exception $e){
            throw new CrawlerFilterException('phpunit.selector_malformed',$selector);
        }
    }

    /**
     * Get text form of this node
     * @return string a text
     */
    public function text()
    {
        return trim(parent::text());
    }

    /**
     * Get element by ID
     * @param   string $id
     * @return  \toolbox\phpunit\Crawler
     */
    public function byId($id)
    {
        if (strpos($id, '#') !== 0) {
            $id = '#' . $id;
        }

        return $this->filter($id);
    }

    /**
     * Get element by CSS Class
     * @param   string $class
     * @return  \toolbox\phpunit\Crawler
     */
    public function byCssClass($class)
    {
        if (strpos($class, '.') !== 0) {
            $class = '.' . $class;
        }
        return $this->filter($class);
    }

    /**
     * Get element by name
     * @param   string $name
     * @return  \toolbox\phpunit\Crawler
     */
    public function byName($name, $element = null)
    {
        $selector = '[name=' . $name . ']';
        if (!is_null($element)) {
            $selector = $element . $selector;
        }
        try{
            return $this->filter($selector);
        }catch(\Exception $e){
            throw new CrawlerFilterException('phpunit.selector_malformed',$selector);
        }
    }

    /**
     * Check if element present
     * @param   string      $selector
     * @return  boolean     True if element present
     */
    public function hasElement($selector)
    {
        if($this->filter($selector)->count()>0){
            return true;
        }elseif($this->byId($selector)->count()>0){
            return true;
        }elseif(strpos($selector,'#') !== 0 && $this->byName($selector)->count()>0){
            return true;
        }elseif($this->byCssClass($selector)->count()>0){
            return true;
        }
        return false;
    }
}

?>
