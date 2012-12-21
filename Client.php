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

}

?>
