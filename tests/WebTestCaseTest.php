<?php

/*
 * This file is part of the toolbox package.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace toolbox\phpunit\tests;

/**
 * Description of WebTestCaseTest
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
class WebTestCaseTest extends BaseTestCase
{
    public function testOpen()
    {
        $this->open('html/test_open.html');
        $this->assertTitle('Test Open');
        $this->assertBodyContains('This is a test of the open command.');

        $this->open('/html/test_open.html');
        $this->assertTitle('Test Open');
    }

    public function testShouldOpenAbsolutePath()
    {
        $this->open(TOOLBOX_PHPUNIT_BASE_TEST_URL.'/html/test_open.html');
        $this->assertTitle('Test Open');
        $this->assertBodyContains('This is a test of the open command.');
    }

    public function testStatusCode()
    {
        $this->open('http://localhost/foo.bar.html');

        $this->assertEquals(404,$this->statusCode());
        $this->assertStatusCode('404', $this->statusCode());
        $this->assertStatusCode(404, $this->statusCode());
    }
}

?>
