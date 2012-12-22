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
    public function testShouldOpenWithBaseUrl()
    {
        $this->open('html/test_open.html');
        $this->assertEquals('Test Open',$this->title());
        $this->assertEquals('This is a test of the open command.',$this->body()->text());

        $this->open('/html/test_open.html');
        $this->assertTitle('Test Open');
    }

    public function testShouldOpenUrlWithAbsolutePath()
    {
        $this->open(TOOLBOX_PHPUNIT_BASE_TEST_URL.'/html/test_open.html');
        $this->assertTitle('Test Open');
        $this->assertBodyContains('This is a test of the open command.');
    }

    public function testShouldReturnStatusCode()
    {
        $this->open('html/test_open.html');
        $this->assertEquals(200,$this->statusCode());

        $this->open('foo/bar');

        $server = strtolower($this->getBrowser()->getResponse()->getHeader('server'));
        if(false===strpos($server,'apache')){
            $this->markTestSkipped();
        }
        $this->assertEquals(404,$this->statusCode());
    }

    public function testShouldSelectElementById()
    {
        $this->open('html/test_element_selection.html');

        $element = $this->byId('theDivId');
        $this->assertTrue($element->count() > 0 );

        $element = $this->byId('#theDivId');
        $this->assertTrue($element->count()>0);
    }

    public function testShouldSelectElementByCssClass()
    {
        $this->open('html/test_element_selection.html');

        $element = $this->byCssClass('theDivClass');
        $this->assertTrue($element->count() > 0);

        $element = $this->byCssClass('.theDivClass');
        $this->assertTrue($element->count() > 0);
    }

    public function testShouldSelectElementByName()
    {
        $this->open('html/test_element_selection.html');

        $element = $this->byName('theDivName');
        $this->assertTrue($element->count() > 0);

        $element = $this->byName('theDivName','div');
        $this->assertTrue($element->count() > 0);
    }

    /**
     * @dataProvider getVerifyElement
     */
    public function testShouldVerifyElementPresent($expected,$selector)
    {
        $this->open('html/test_element_selection.html');
        $this->assertEquals($expected,$this->hasElement($selector));
    }

    public function getVerifyElement()
    {
        return array(
            array(true,'theDivName'),
            array(false,'#theDivName'),
            array(true,'theDivId'),
            array(true,'theDivClass'),
            array(true,'.theDivClass'),
            array(false,'foodiv'),
        );
    }
}

?>
