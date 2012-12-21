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
}

?>
