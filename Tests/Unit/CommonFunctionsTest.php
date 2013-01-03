<?php
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
