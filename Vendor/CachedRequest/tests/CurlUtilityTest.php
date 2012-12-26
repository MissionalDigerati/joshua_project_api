<?php
require_once(__DIR__."/../CurlUtility.php");
/**
 * A test for the CurlUtility class
 *
 * @package default
 * @author Johnathan Pulos
 */
class CurlUtilityTest extends PHPUnit_Framework_TestCase {
	/**
	 * The CurlUtility Object
	 *
	 * @var object
	 * @access private
	 */
	public $curlUtility;
	
	/**
	 * Setup the testing environment
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function setUp() {
		$this->curlUtility = new CurlUtility();
	}
	
	/**
	 * Tests makeRequest() to assure it returns data
	 *
	 * @covers CurlUtility::makeRequest
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testMakeRequestShouldSendRequest() {
		$results = $this->curlUtility->makeRequest("http://feeds.feedburner.com/GiantRobotsSmashingIntoOtherGiantRobots", "GET");
		$this->assertTrue($results != '');
		$this->assertEquals($this->curlUtility->responseCode, 200);
	}
	
	/**
	 * Tests urlify actually urlifies an array
	 *
	 * @covers CurlUtility::urlify
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testUrlifyShouldConvertArrayToGETString() {
		$expected = "me=Johnathan&you=Programmer";
		$actual = $this->curlUtility->urlify(array("me" => "Johnathan", "you" => "Programmer"));
		$this->assertEquals($expected, $actual);
	}
}
?>