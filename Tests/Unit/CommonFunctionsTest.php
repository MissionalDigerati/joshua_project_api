<?php
/**
 * This file is part of Joshua Project API.
 * 
 * Joshua Project API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Joshua Project API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
namespace Tests\Unit;

/**
 * Test the common functions file
 *
 * @author Johnathan Pulos
 */
class CommonFunctionsTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * arrayToXML must return a valid XML structure
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testArrayToXMLShouldCreateCorrectXML()
    {
        $expected = "<?xml version=\"1.0\"?><api><tests><test><name>test arrayToXML</name></test></tests></api>";
        $actual = arrayToXML(array("data" => array("name" => "test arrayToXML")), "tests", "test");
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * arrayToXML should default the wrappers accordingly
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testArrayToXMLShouldDefaultWrappers()
    {
        $expected = "<?xml version=\"1.0\"?><api><items><item><name>test arrayToXML</name></item></items></api>";
        $actual = arrayToXML(array("data" => array("name" => "test arrayToXML")));
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test that returnPresentOrDefault returns the passed variable
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testReturnPresentOrDefaultShouldReturnPresentedVar()
    {
        $variable = "Frog";
        $default = "Cow";
        $expected = $variable;
        $actual = returnPresentOrDefault($variable, $default);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test that returnPresentOrDefault returns the passed variable
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testReturnPresentOrDefaultShouldReturnDefaultVar()
    {
        $variable = "";
        $default = "default";
        $expected = $default;
        $actual = returnPresentOrDefault($variable, $default);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests whether validatePresenceOf() returns all required fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testValidatePresenceShouldReturnAllFieldsIfEmpty()
    {
        $expected = array("name", "email", "usage");
        $requiredFields = array("name", "email", "usage");
        $formData = array("name" => "", "email" => "", "usage" => "");
        $actual = validatePresenceOf($requiredFields, $formData);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests whether validatePresenceOf() returns only empty fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testValidatePresenceOfShouldReturnOnlyEmptyFields()
    {
        $expected = array("name");
        $requiredFields = array("name", "email", "usage");
        $formData = array("name" => "", "email" => "joe@yahoo.com", "usage" => "My website.");
        $actual = validatePresenceOf($requiredFields, $formData);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that the valid URL is created based on invalid params for generateAPIKeyRedirectURL
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testGenerateAPIKeyRedirectURLCreatesCorrectURLWithInvalidParams()
    {
        $expected = "/?required_fields=name|email|usage";
        $formData = array("name" => "", "email" => "", "usage" => "");
        $invalidFields = array("name", "email", "usage");
        $actual = generateAPIKeyRedirectURL($formData, $invalidFields);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that the valid URL is created based on all valid params for generateAPIKeyRedirectURL
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testGenerateAPIKeyRedirectURLCreatesCorrectURLWithValidParams()
    {
        $expected = "/?name=Joe&email=joe%40yahoo.com&usage=Free+Willie";
        $formData = array("name" => "Joe", "email" => "joe@yahoo.com", "usage" => "Free Willie");
        $invalidFields = array();
        $actual = generateAPIKeyRedirectURL($formData, $invalidFields);
        $this->assertEquals($expected, $actual);
    }
}
