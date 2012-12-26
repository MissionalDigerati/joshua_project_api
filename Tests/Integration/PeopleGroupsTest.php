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
		$response = $this->cachedRequest->get("http://joshua.api.local/people_groups/daily_unreached.json", array(), "unreached_people_json");
		$this->assertEquals($this->cachedRequest->responseCode, 200);
		$this->assertTrue(isJSON($response));
	 }

}
?>