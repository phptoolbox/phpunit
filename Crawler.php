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
    public function text()
    {
        return trim(parent::text());
    }

    /**
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
     * @param   string $name
     * @return  \toolbox\phpunit\Crawler
     */
    public function byName($name, $element = null)
    {
        $selector = '[name=' . $name . ']';
        if (!is_null($element)) {
            $selector = $element . $selector;
        }

        return $this->filter($selector);
    }
}

?>
