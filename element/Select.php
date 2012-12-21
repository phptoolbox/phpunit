<?php

/*
 * This file is part of the toolbox-phpunit package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace toolbox\phpunit\element;

use toolbox\phpunit\Crawler;

use DOMElement;

/**
 * Description of Select
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class Select extends DOMElement
{
    private $crawler;

    /**
     * @param   \toolbox\phpunit\Crawler        $crawler
     */
    public function __construct(Crawler &$crawler)
    {
        $this->crawler = &$crawler;
    }

    /**
     * @return array Selected value label
     */
    public function selectedLabels()
    {
        return $this->crawler->filter(':checked')->extract('_text');
    }

    public function clearSelectedOptions()
    {
    }
}

?>
