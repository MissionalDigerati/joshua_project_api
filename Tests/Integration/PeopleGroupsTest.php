<?php
require_once('Tests/Support/HelperFunctions.php');
require_once('Vendor/PHPToolbox/CachedRequest/CachedRequest.php');
/**
 * The class for testing integration of the People Groups
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroupsTest extends PHPUnit_Framework_TestCase {
    /**
     * The CachedRequest Object
     *
     * @var object
     */
	public $cachedRequest;

	/**
	 * Set up the test class
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function setUp() {
		$this->cachedRequest = new CachedRequest();
		$this->cachedRequest->cacheDirectory = __DIR__ . "/../Support/cache/";
	}

	/**
	* Runs at the end of each test
	*
	* @return void
	* @access public
	* @author Johnathan Pulos
	*/
	public function tearDown() {
		$this->cachedRequest->clearCache();
	}

	 /**
	  * GET /people_groups/daily_unreached.json 
	  * test page is available, and delivers JSON
	  *
	  * @return void
	  * @access public
	  * @author Johnathan Pulos
	  */
	public function testShouldGetDailyUnreachedInJSON() {
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.json", array(), "up_json");
		$this->assertEquals($this->cachedRequest->responseCode, 200);
		$this->assertTrue(isJSON($response));
	}
	
	 /**
	  * GET /people_groups/daily_unreached.xml 
	  * test page is available, and delivers XML
	  *
	  * @return void
	  * @access public
	  * @author Johnathan Pulos
	  */
	public function testShouldGetDailyUnreachedInXML() {
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.xml", array(), "up_xml");
		$this->assertEquals($this->cachedRequest->responseCode, 200);
		$this->assertTrue(isXML($response));
	}
	
	/**
	 * A request for Daily Unreached should allow setting the month
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testShouldGetDailyUnreachedWithSetMonth() {
		$expectedMonth = '5';
		$expectedDay = Date('j');
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.json?month=".$expectedMonth, array(), "up_month");
		$decodedResponse = json_decode($response, true);
		$this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
		$this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
	}
	
	/**
	 * A request for Daily Unreached should allow setting the day
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testShouldGetDailyUnreachedWithSetDay() {
		$expectedMonth = Date('n');
		$expectedDay = '23';
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.json?day=".$expectedDay, array(), "up_day");
		$decodedResponse = json_decode($response, true);
		$this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
		$this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
	}
	
	/**
	 * A request for Daily Unreached should allow setting the day and month
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testShouldGetDailyUnreachedWithSetDayAndMonth() {
		$expectedMonth = '3';
		$expectedDay = '21';
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.json?day=".$expectedDay."&month=".$expectedMonth, array(), "up_day_and_month");
		$decodedResponse = json_decode($response, true);
		$this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
		$this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
	}

}
?>