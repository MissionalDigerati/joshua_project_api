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
}
