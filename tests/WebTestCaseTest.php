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
        $this->url('html/test_open.html');
        $this->assertStringEndsWith('html/test_open.html', $this->url());
    }

    public function testVersionCanBeReadFromTheTestCaseClass()
    {
        $this->assertEquals(1, version_compare(\toolbox\phpunit\WebTestCase::VERSION, "1.0.0"));
    }

    public function testCamelCaseUrlsAreSupported()
    {
        $this->url('html/CamelCasePage.html');
        $this->assertStringEndsWith('html/CamelCasePage.html', $this->url());
        $this->assertEquals('CamelCase page', $this->title());
    }

    public function testAbsoluteUrlsAreSupported()
    {
        $this->url(PHPUNIT_TESTSUITE_EXTENSION_SELENIUM_TESTS_URL . 'html/test_open.html');
        $this->assertEquals('Test open', $this->title());
    }

    public function testElementSelection()
    {
        $this->url('html/test_open.html');
        $element = $this->byCssSelector('body');
        $this->assertEquals('This is a test of the open command.', $element->text());

        $this->url('html/test_click_page1.html');
        $link = $this->byId('link');
        $this->assertEquals('Click here for next page', $link->text());
    }

    public function testMultipleElementsSelection()
    {
        $this->url('html/test_element_selection.html');
        $elements = $this->elements($this->using('css selector')->value('div'));
        $this->assertEquals(4, count($elements));
        $this->assertEquals('Other div', $elements[0]->text());
    }

    public function testClearMultiselectSelectedOptions()
    {
        $this->url('html/test_multiselect.html');
        $selectedOptions = $this->select($this->byId('theSelect'))->selectedLabels();
        $this->assertEquals(array('Second Option'), $selectedOptions);
        $this->select($this->byId('theSelect'))->clearSelectedOptions();
        $selectedOptions = $this->select($this->byId('theSelect'))->selectedLabels();
        $this->assertEquals(array(), $selectedOptions);
    }

    public function testTheElementWithFocusCanBeInspected()
    {
        $this->markTestIncomplete('Which API to call session/1/element/active?');
        $this->keys(array('value' => array())); // should send key strokes to the active element
    }

    public function testElementFromResponseValue()
    {
        $this->url('html/test_open.html');
        $elementArray = $this->execute(array(
            'script' => 'return document.body;',
            'args' => array(),
                ));
        $element = $this->elementFromResponseValue($elementArray);
        $this->assertEquals('This is a test of the open command.', $element->text());
    }

    public function testActivePageElementReceivesTheKeyStrokes()
    {
        $this->timeouts()->implicitWait(10000);

        $this->url('html/test_send_keys.html');
        $this->byId('q')->click();
        $this->keys('phpunit ');
        $this->assertEquals('phpunit', $this->byId('result')->text());
    }

    public function testElementsCanBeSelectedAsChildrenOfAlreadyFoundElements()
    {
        $this->url('html/test_element_selection.html');
        $parent = $this->byCssSelector('div#parentElement');
        $child = $parent->element($this->using('css selector')->value('span'));
        $this->assertEquals('Child span', $child->text());

        $rows = $this->byCssSelector('table')->elements($this->using('css selector')->value('tr'));
        $this->assertEquals(2, count($rows));
    }

    public function testShortenedApiForSelectionOfElement()
    {
        $this->url('html/test_element_selection.html');

        $element = $this->byClassName('theDivClass');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byCssSelector('div.theDivClass');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byId('theDivId');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byName('theDivName');
        $this->assertEquals('The right div', $element->text());

        $element = $this->byXPath('//div[@id]');
        $this->assertEquals('The right div', $element->text());
    }

    public function testElementsKnowTheirTagName()
    {
        $this->url('html/test_element_selection.html');
        $element = $this->byClassName('theDivClass');
        $this->assertEquals('div', $element->name());
    }

    public function testFormElementsKnowIfTheyAreEnabled()
    {
        $this->url('html/test_form_elements.html');
        $this->assertTrue($this->byId('enabledInput')->enabled());
        $this->assertFalse($this->byId('disabledInput')->enabled());
    }

    public function testElementsKnowTheirAttributes()
    {
        $this->url('html/test_element_selection.html');
        $element = $this->byId('theDivId');
        $this->assertEquals('theDivClass', $element->attribute('class'));
    }

    public function testElementsDiscoverTheirEqualityWithOtherElements()
    {
        $this->url('html/test_element_selection.html');
        $element = $this->byId('theDivId');
        $differentElement = $this->byId('parentElement');
        $equalElement = $this->byId('theDivId');
        $this->assertTrue($element->equals($equalElement));
        $this->assertFalse($element->equals($differentElement));
    }

    public function testElementsKnowWhereTheyAreInThePage()
    {
        $this->url('html/test_element_selection.html');
        $element = $this->byCssSelector('body');
        $location = $element->location();
        $this->assertEquals(0, $location['x']);
        $this->assertEquals(0, $location['y']);
    }

    public function testElementsKnowTheirSize()
    {
        $this->url('html/test_geometry.html');
        $element = $this->byId('rectangle');
        $size = $element->size();
        $this->assertEquals(200, $size['width']);
        $this->assertEquals(100, $size['height']);
    }

    public function testElementsKnowTheirCssPropertiesValues()
    {
        $this->url('html/test_geometry.html');
        $element = $this->byId('colored');
        $this->assertRegExp('/rgba\(0,\s*0,\s*255,\s*1\)/', $element->css('background-color'));
    }

    public function testClick()
    {
        $this->url('html/test_click_page1.html');
        $link = $this->byId('link');
        $link->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();
        $this->assertEquals('Click Page 1', $this->title());

        $withImage = $this->byId('linkWithEnclosedImage');
        $withImage->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();

        $enclosedImage = $this->byId('enclosedImage');
        $enclosedImage->click();
        $this->assertEquals('Click Page Target', $this->title());
        $back = $this->byId('previousPage');
        $back->click();

        $toAnchor = $this->byId('linkToAnchorOnThisPage');
        $toAnchor->click();
        $this->assertEquals('Click Page 1', $this->title());

        $withOnClick = $this->byId('linkWithOnclickReturnsFalse');
        $withOnClick->click();
        $this->assertEquals('Click Page 1', $this->title());
    }

    public function testByLinkText()
    {
        $this->url('html/test_click_page1.html');
        $link = $this->byLinkText('Click here for next page');
        $link->click();
        $this->assertEquals('Click Page Target', $this->title());
    }

    public function testClicksOnJavaScriptHref()
    {
        $this->url('html/test_click_javascript_page.html');
        $this->clickOnElement('link');
        $this->assertEquals('link clicked', $this->alertText());
        $this->markTestIncomplete("Should guarantee alerts to be checked in the right order and be dismissed; should reset the session in case alerts are still displayed as they would block the next test.");

        $this->clickOnElement('linkWithMultipleJavascriptStatements');
        $this->assertEquals('alert1', $this->alertText());
        $this->acceptAlert();
        $this->assertEquals('alert2', $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('alert3', $this->alertText());

        $this->clickOnElement('linkWithJavascriptVoidHref');
        $this->assertEquals('onclick', $this->alertText());
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('linkWithOnclickReturnsFalse');
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('enclosedImage');
        $this->assertEquals('enclosedImage clicked', $this->alertText());
    }

    public function testTypingViaTheKeyboard()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('TestUser');
        $this->assertEquals('TestUser', $usernameInput->value());

        $passwordInput = $this->byName('password');
        $passwordInput->value('testUserPassword');
        $this->assertEquals('testUserPassword', $passwordInput->value());

        $this->clickOnElement('submitButton');
        $h2 = $this->byCssSelector('h2');
        $this->assertRegExp('/Welcome, TestUser!/', $h2->text());
    }

    /**
     * #190
     */
    public function testTypingAddsCharactersToTheCurrentValueOfAnElement()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('first');
        $usernameInput->value('second');
        $this->assertEquals('firstsecond', $usernameInput->value());
    }

    /**
     * #165
     */
    public function testNumericValuesCanBeTyped()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value(1.13);
    }

    public function testFormsCanBeSubmitted()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('TestUser');

        $this->byCssSelector('form')->submit();
        $h2 = $this->byCssSelector('h2');
        $this->assertRegExp('/Welcome, TestUser!/', $h2->text());
    }

    /**
     * @depends testTypingViaTheKeyboard
     */
    public function testTextTypedInAreasCanBeCleared()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('TestUser');
        $usernameInput->clear();
        $this->assertEquals('', $usernameInput->value());
    }

    public function testTypingNonLatinText()
    {
        $this->url('html/test_type_page1.html');
        $usernameInput = $this->byName('username');
        $usernameInput->value('テストユーザ');
        $this->assertEquals('テストユーザ', $usernameInput->value());
    }

    public function testSelectElements()
    {
        $this->url('html/test_select.html');
        $option = $this->byId('o2');
        $this->assertEquals('Second Option', $option->text());
        $this->assertEquals('option2', $option->value());
        $this->assertTrue($option->selected());
        $option = $this->byId('o3');
        $this->assertFalse($option->selected());
        $option->click();
        $this->assertTrue($option->selected());
    }

    public function testASelectObjectCanBeBuildWithASpecificAPI()
    {
        $this->url('html/test_select.html');
        $select = $this->select($this->byCssSelector('select'));

        // basic
        $this->assertEquals('Second Option', $select->selectedLabel());
        $this->assertEquals('option2', $select->selectedValue());

        // by text, value attribute or generic criteria
        $select->selectOptionByLabel('Fourth Option');
        $this->assertEquals('option4', $select->selectedValue());

        $select->selectOptionByValue('option3');
        $this->assertEquals('Third Option', $select->selectedLabel());

        $select->selectOptionByCriteria($this->using('id')->value('o4'));
        $this->assertEquals('option4', $select->selectedValue());

        // empty values
        $select->selectOptionByValue('');
        $this->assertEquals('Empty Value Option', $select->selectedLabel());

        $select->selectOptionByLabel('');
        $this->assertEquals('', $select->selectedLabel());
    }

    /**
     * Ticket 119
     */
    public function testSelectOptionSelectsDescendantElement()
    {
        $this->url('html/test_select.html');
        $select = $this->select($this->byCssSelector('#secondSelect'));
        $this->assertEquals("option2", $select->selectedValue());

        $select->selectOptionByLabel("First Option");
        $this->assertEquals("option1", $select->selectedValue());

        $select->selectOptionByValue("option2");
        $this->assertEquals("option2", $select->selectedValue());
    }

    /**
     * Ticket 170
     */
    public function testSelectOptgroupDoNotGetInTheWay()
    {
        $this->url('html/test_select.html');
        $select = $this->select($this->byCssSelector('#selectWithOptgroup'));

        $select->selectOptionByLabel("Second");
        $this->assertEquals("2", $select->selectedValue());

        $select->selectOptionByValue("1");
        $this->assertEquals("1", $select->selectedValue());
    }

    public function testCheckboxesCanBeSelectedAndDeselected()
    {
        $this->markTestIncomplete("Flaky: fails on clicking in some browsers.");
        $this->url('html/test_check_uncheck.html');
        $beans = $this->byId('option-beans');
        $butter = $this->byId('option-butter');

        $this->assertTrue($beans->selected());
        $this->assertFalse($butter->selected());

        $butter->click();
        $this->assertTrue($butter->selected());
        $butter->click();
        $this->assertFalse($butter->selected());
    }

    public function testRadioBoxesCanBeSelected()
    {
        $this->url('html/test_check_uncheck.html');
        $spud = $this->byId('base-spud');
        $rice = $this->byId('base-rice');

        $this->assertTrue($spud->selected());
        $this->assertFalse($rice->selected());

        $rice->click();
        $this->assertFalse($spud->selected());
        $this->assertTrue($rice->selected());

        $spud->click();
        $this->assertTrue($spud->selected());
        $this->assertFalse($rice->selected());
    }

    public function testWaitPeriodsAreImplicitInSelection()
    {
        $this->timeouts()->implicitWait(10000);
        $this->url('html/test_delayed_element.html');
        $element = $this->byId('createElementButton')->click();
        $div = $this->byXPath("//div[@id='delayedDiv']");
        $this->assertEquals('Delayed div.', $div->text());
    }

    public function testTimeoutsCanBeDefinedForAsynchronousExecutionOfJavaScript()
    {
        $this->url('html/test_open.html');
        $this->timeouts()->asyncScript(10000);
        $script = 'var callback = arguments[0];
                   window.setTimeout(function() {
                       callback(document.title);
                   }, 1000);
        ';
        $result = $this->executeAsync(array(
            'script' => $script,
            'args' => array()
                ));
        $this->assertEquals("Test open", $result);
    }

    public function testTheBackAndForwardButtonCanBeUsedToNavigate()
    {
        $this->url('html/test_click_page1.html');
        $this->assertEquals('Click Page 1', $this->title());

        $this->clickOnElement('link');
        $this->assertEquals('Click Page Target', $this->title());

        $this->back();
        $this->assertEquals('Click Page 1', $this->title());

        $this->forward();
        $this->assertEquals('Click Page Target', $this->title());
    }

    public function testThePageCanBeRefreshed()
    {
        $this->url('html/test_page.slow.html');
        $this->assertStringEndsWith('html/test_page.slow.html', $this->url());
        $this->assertEquals('Slow Loading Page', $this->title());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->byId('theSpan')->text());
        $this->refresh();
        $this->assertEquals('This is a slow-loading page.', $this->byId('theSpan')->text());

        $this->clickOnElement('changeSpan');
        $this->assertEquals('Changed the text', $this->byId('theSpan')->text());
    }

    public function testLinkEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $eventLog->value());
        $this->clickOnElement('theLink');
        $this->assertEquals('link clicked', $this->alertText());
        $this->acceptAlert();
        $this->assertContains('{click(theLink)}', $eventLog->value());
    }

    public function testButtonEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $eventLog->value());
        $this->clickOnElement('theButton');
        $this->assertContains('{focus(theButton)}', $eventLog->value());
        $this->assertContains('{click(theButton)}', $eventLog->value());
        $eventLog->clear();

        $this->clickOnElement('theSubmit');
        $this->assertContains('{focus(theSubmit)} {click(theSubmit)} {submit}', $eventLog->value());
    }

    public function testSelectEventsAreGeneratedbutOnlyIfANewSelectionIsMade()
    {
        $this->url('html/test_form_events.html');
        $select = $this->select($this->byId('theSelect'));
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $select->selectedValue());
        $this->assertEquals('', $eventLog->value());

        $select->selectOptionByLabel('First Option');
        $this->assertEquals('option1', $select->selectedValue());
        $this->assertContains('{focus(theSelect)}', $eventLog->value());
        $this->assertContains('{change(theSelect)}', $eventLog->value());

        $eventLog->clear();
        $select->selectOptionByLabel('First Option');
        $this->assertEquals('option1', $select->selectedValue());
        $this->assertEquals('', $eventLog->value());
    }

    public function testRadioEventsAreGenerated()
    {
        $this->markTestIncomplete("Flaky: fails on focus in some browsers.");
        $this->url('html/test_form_events.html');
        $first = $this->byId('theRadio1');
        $second = $this->byId('theRadio2');
        $eventLog = $this->byId('eventlog');

        $this->assertFalse($first->selected());
        $this->assertFalse($second->selected());
        $this->assertEquals('', $eventLog->value());

        $first->click();
        $this->assertContains('{focus(theRadio1)}', $eventLog->value());
        $this->assertContains('{click(theRadio1)}', $eventLog->value());
        $this->assertContains('{change(theRadio1)}', $eventLog->value());
        $this->assertNotContains('theRadio2', $eventLog->value());

        $eventLog->clear();
        $first->click();
        $this->assertContains('{focus(theRadio1)}', $eventLog->value());
        $this->assertContains('{click(theRadio1)}', $eventLog->value());
    }

    public function testCheckboxEventsAreGenerated()
    {
        $this->markTestIncomplete("Flaky: fails on focus in some browsers.");
        $this->url('html/test_form_events.html');
        $checkbox = $this->byId('theCheckbox');
        $eventLog = $this->byId('eventlog');
        $this->assertFalse($checkbox->selected());
        $this->assertEquals('', $eventLog->value());

        $checkbox->click();
        $this->assertContains('{focus(theCheckbox)}', $eventLog->value());
        $this->assertContains('{click(theCheckbox)}', $eventLog->value());
        $this->assertContains('{change(theCheckbox)}', $eventLog->value());

        $eventLog->clear();
        $checkbox->click();
        $this->assertContains('{focus(theCheckbox)}', $eventLog->value());
        $this->assertContains('{click(theCheckbox)}', $eventLog->value());
        $this->assertContains('{change(theCheckbox)}', $eventLog->value());
    }

    public function testTextEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $textBox = $this->byId('theTextbox');
        $eventLog = $this->byId('eventlog');
        $this->assertEquals('', $textBox->value());
        $this->assertEquals('', $eventLog->value());

        $textBox->value('first value');
        $this->assertContains('{focus(theTextbox)}', $eventLog->value());
    }

    public function testMouseEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $this->clickOnElement('theTextbox');
        $this->clickOnElement('theButton');
        $eventLog = $this->byId('eventlog');
        $this->assertContains('{mouseover(theTextbox)}', $eventLog->value());
        $this->assertContains('{mousedown(theButton)}', $eventLog->value());
        $this->assertContains('{mouseover(theTextbox)}', $eventLog->value());
        $this->assertContains('{mousedown(theButton)}', $eventLog->value());
    }

    public function testKeyEventsAreGenerated()
    {
        $this->url('html/test_form_events.html');
        $this->byId('theTextbox')->value('t');

        $this->assertContains('{focus(theTextbox)}'
                . ' {keydown(theTextbox - 84)}'
                . ' {keypress(theTextbox)}'
                . ' {keyup(theTextbox - 84)}', $this->byId('eventlog')->value());
    }

    public function testConfirmationsAreHandledAsAlerts()
    {
        $this->url('html/test_confirm.html');
        $this->clickOnElement('confirmAndLeave');
        $this->assertEquals('You are about to go to a dummy page.', $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('Test Confirm', $this->title());

        $this->clickOnElement('confirmAndLeave');
        $this->assertEquals('You are about to go to a dummy page.', $this->alertText());
        $this->acceptAlert();
        $this->assertEquals('Dummy Page', $this->title());
    }

    public function testPromptsCanBeAnsweredByTyping()
    {
        $this->url('html/test_prompt.html');

        $this->clickOnElement('promptAndLeave');
        $this->assertEquals("Type 'yes' and click OK", $this->alertText());
        $this->dismissAlert();
        $this->assertEquals('Test Prompt', $this->title());

        $this->clickOnElement('promptAndLeave');
        $this->alertText('yes');
        $this->acceptAlert();
        $this->assertEquals('Dummy Page', $this->title());
    }

    public function testInvisibleElementsDoNotHaveADisplayedText()
    {
        $this->url('html/test_visibility.html');
        $this->assertEquals('A visible paragraph', $this->byId('visibleParagraph')->text());
        $this->assertTrue($this->byId('visibleParagraph')->displayed());

        $this->assertEquals('', $this->byId('hiddenParagraph')->text());
        $this->assertFalse($this->byId('hiddenParagraph')->displayed());

        $this->assertEquals('', $this->byId('suppressedParagraph')->text());
        $this->assertEquals('', $this->byId('classSuppressedParagraph')->text());
        $this->assertEquals('', $this->byId('jsClassSuppressedParagraph')->text());
        $this->assertEquals('', $this->byId('hiddenSubElement')->text());
        $this->assertEquals('sub-element that is explicitly visible', $this->byId('visibleSubElement')->text());
        $this->assertEquals('', $this->byId('suppressedSubElement')->text());
        $this->assertEquals('', $this->byId('jsHiddenParagraph')->text());
    }

    public function testScreenshotsCanBeTakenAtAnyMoment()
    {
        $this->url('html/test_open.html');
        $screenshot = $this->currentScreenshot();
        $this->assertTrue(is_string($screenshot));
        $this->assertTrue(strlen($screenshot) > 0);
        $this->markTestIncomplete('By guaranteeing the size of the window, we could add a deterministic assertion for the image.');
    }

    public function testACurrentWindowHandleAlwaysExist()
    {
        $this->url('html/test_open.html');
        $window = $this->windowHandle();
        $this->assertTrue(is_string($window));
        $this->assertTrue(strlen($window) > 0);
        $allHandles = $this->windowHandles();
        $this->assertEquals(array('0' => $window), $allHandles);
    }

    public function testThePageSourceCanBeRead()
    {
        $this->url('html/test_open.html');
        $source = $this->source();
        $this->assertStringStartsWith('<!--', $source);
        $this->assertContains('<body>', $source);
        $this->assertStringEndsWith('</html>', $source);
    }

    public function testJavaScriptCanBeEmbeddedForExecution()
    {
        $this->url('html/test_open.html');
        $script = 'return document.title;';
        $result = $this->execute(array(
            'script' => $script,
            'args' => array()
                ));
        $this->assertEquals("Test open", $result);
    }

    public function testAsynchronousJavaScriptCanBeEmbeddedForExecution()
    {
        $this->url('html/test_open.html');
        $script = 'var callback = arguments[0]; callback(document.title);';
        $result = $this->executeAsync(array(
            'script' => $script,
            'args' => array()
                ));
        $this->assertEquals("Test open", $result);
    }

    public function testInputMethodFrameworksCanBeManagedViaTheApi()
    {
        $this->markTestIncomplete("Need to create an IME object.");
        $this->ime()->availableEngines();
        $this->ime()->activeEngine();
        $this->ime()->activated();
        $this->ime()->deactive();
        $this->ime()->activate();
    }

    public function testDifferentFramesFromTheMainOneCanGetFocus()
    {
        $this->url('html/test_frames.html');
        $this->frame('my_iframe_id');
        $this->assertEquals('This is a test of the open command.', $this->byCssSelector('body')->text());

        $this->frame(null);
        $this->assertContains('This page contains frames.', $this->byCssSelector('body')->text());
    }

    public function testDifferentWindowsCanBeFocusedOnDuringATest()
    {
        $this->url('html/test_select_window.html');
        $this->byId('popupPage')->click();

        $this->window('myPopupWindow');
        $this->assertEquals('Select Window Popup', $this->title());

        $this->window('');
        $this->assertEquals('Select Window Base', $this->title());

        $this->window('myPopupWindow');
        $this->byId('closePage')->click();
    }

    public function testWindowsCanBeManipulatedAsAnObject()
    {
        $this->url('html/test_select_window.html');
        $this->byId('popupPage')->click();

        $this->window('myPopupWindow');
        $popup = $this->currentWindow();
        $this->assertTrue($popup instanceof PHPUnit_Extensions_Selenium2TestCase_Window);
        $popup->size(array('width' => 100, 'height' => 200));
        $size = $popup->size();
        $this->assertEquals(100, $size['width']);
        $this->assertEquals(200, $size['height']);


        $this->markTestIncomplete("We should wait for the window to be moved. How? With aynshcrnous javascript specific for this test");
        //$popup->position(array('x' => 300, 'y' => 400));
        //$position = $popup->position();
        //$this->assertEquals(300, $position['x']);
        //$this->assertEquals(400, $position['y']);
        // method on Window; interface Closeable, better name?
    }

    public function testWindowsCanBeClosed()
    {
        $this->url('html/test_select_window.html');
        $this->byId('popupPage')->click();

        $this->window('myPopupWindow');
        $this->closeWindow();

        $this->assertEquals(1, count($this->windowHandles()));
    }

    public function testCookiesCanBeSetAndRead()
    {
        $this->url('html/');
        $cookies = $this->cookie();
        $cookies->add('name', 'value')->set();
        $this->assertEquals('value', $cookies->get('name'));
    }

    /**
     * @depends testCookiesCanBeSetAndRead
     */
    public function testCookiesCanBeDeletedOneAtTheTime()
    {
        $this->url('html/');
        $cookies = $this->cookie();
        $cookies->add('name', 'value')->set();
        $cookies->remove('name');
        $this->assertThereIsNoCookieNamed('name');
    }

    public function testCookiesCanBeDeletedAllAtOnce()
    {
        $this->url('html/');
        $cookies = $this->cookie();
        $cookies->add('id', 'id_value')->set();
        $cookies->add('name', 'name_value')->set();
        $cookies->clear();
        $this->assertThereIsNoCookieNamed('id');
        $this->assertThereIsNoCookieNamed('name');
    }

    public function testAdvancedParametersOfCookieCanBeSet()
    {
        $this->url('/');
        $cookies = $this->cookie();
        $cookies->add('name', 'value')
                ->path('/html')
                ->domain('127.0.0.1')
                ->expiry(time() + 60 * 60 * 24)
                ->secure(FALSE)
                ->set();
        $this->assertThereIsNoCookieNamed('name');
        $this->url('/html');
        $this->assertEquals('value', $cookies->get('name'));
    }

    private function assertThereIsNoCookieNamed($name)
    {
        try {
            $this->cookie()->get($name);
            $this->fail('The cookie shouldn\'t exist anymore.');
        }
        catch (PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
            $this->assertEquals("There is no '$name' cookie available on this page.", $e->getMessage());
        }
    }

    public function testTheBrowsersOrientationCanBeModified()
    {
        $this->markTestIncomplete('Which browsers support this functionality?');
        $this->orientation('LANDSCAPE');
        $this->orientation('PORTRAIT');
        $this->orientation();
    }

    public function testTheMouseCanBeMovedToAKnownPosition()
    {
        $this->markTestIncomplete();
        $this->moveTo(array(
            'element' => 'id', // or Element object
            'xoffset' => 0,
            'yofsset' => 0
        ));
        $this->click();
    }

    public function testMouseButtonsCanBeHeldAndReleasedOverAnElement()
    {
        $this->url('html/movements.html');
        $this->moveto($this->byId('to_move'));
        $this->buttondown();
        $this->moveto($this->byId('target'));
        $this->buttonup();
        $this->markTestIncomplete('Should write something in the input, but while manually drag and drop does work, it doesn\'t with this commands.');
    }

    public function testMouseButtonsCanBeClickedMultipleTimes()
    {
        $this->markTestIncomplete();
        $this->moveTo(array(
            'element' => 'id', // or Element object
            'xoffset' => 0,
            'yofsset' => 0
        ));
        $this->doubleClick();
    }

    public function testFingersCanBeMovedAndPressedOnTheScreen()
    {
        $this->markTestIncomplete('Which browser supports these events?');
        $this->touch()->click();
        $this->touch()->down();
        $this->touch()->up();
        $this->touch()->move();
        $this->touch()->scroll();
        $this->touch()->doubleClick();
        $this->touch()->longClick();
        $this->touch()->flick();
    }

    public function testGeoLocationIsAccessible()
    {
        $this->markTestIncomplete();
        $this->location();
    }

    public function testTheBrowserLocalStorageIsAccessible()
    {
        $this->markTestIncomplete('We need a browser which supports WebStorage.');
        //$this->localStorage(); // all keys
        $storage = $this->localStorage();
        $storage->key = 42;
        $this->assertSame("42", $storage->key);
        //$this->localStorage()->size(); // a value
        // how to clear the storage?
    }

    public function testTheBrowserSessionStorageIsAccessible()
    {
        $this->markTestIncomplete();
        $this->sessionStorage(); // all keys
        $this->sessionStorage()->key; // gets a value
        $this->sessionStorage()->key = 2; // sets a value
        $this->sessionStorage()->size(); // a value
        // how to clear the storage?
    }

    public function test404PagesCanBeLoaded()
    {
        $this->url('inexistent.html');
    }

    /**
     * Ticket #113.
     */
    public function testMultipleUrlsCanBeLoadedInATest()
    {
        $this->url('html/test_click_page1.html');
        $this->url('html/test_open.html');
        $this->assertEquals('Test open', $this->title());
        $this->assertStringEndsWith('html/test_open.html', strstr($this->url(), 'html/'));
    }

    public function testNonexistentElement()
    {
        $this->url('html/test_open.html');
        try {
            $el = $this->byId("nonexistent");
        }
        catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $e->getCode());
            return;
        }
        $this->fail('The element shouldn\'t exist.');
    }
}

?>
