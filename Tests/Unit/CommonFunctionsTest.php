<?php
/**
 * Test the common functions file
 *
 * @author Johnathan Pulos
 */
require_once 'App/Includes/common_functions.php';
class CommonFunctionsTest extends PHPUnit_Framework_TestCase {
    
    /**
     * arrayToXML must return a valid XML structure
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testArrayToXMLCreatesCorrectXML() {
        $expected = new SimpleXMLElement("<api><tests><test><name>test arrayToXML</name></test></tests></api>");
        $actual = arrayToXML(array("data" => array("name" => "test arrayToXML")), "tests", "test");
        $this->assertEquals($expected->asXML(), $actual);
    }
    
    /**
     * arrayToXML should default the wrappers accordingly
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testArrayToXMLDefaultsWraps() {
        $expected = new SimpleXMLElement("<api><items><item><name>test arrayToXML</name></item></items></api>");
        $actual = arrayToXML(array("data" => array("name" => "test arrayToXML")));
        $this->assertEquals($expected->asXML(), $actual);
    }

}