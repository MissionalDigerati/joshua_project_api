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
namespace Tests\Integration;

/**
 * The class for testing integration of the People Groups
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroupsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var object
     */
    public $cachedRequest;
    /**
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private $APIKey = '';
    /**
     * The PDO database connection object
     *
     * @var object
     */
    private $db;
    /**
     * Set up the test class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        $this->cachedRequest = new \PHPToolbox\CachedRequest\CachedRequest;
        $this->cachedRequest->cacheDirectory =
            __DIR__ .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
        $this->setAPIKey();
        $pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
        $pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
        $this->db = $pdoDb->getDatabaseInstance();
    }
    /**
     * Runs at the end of each test
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function tearDown()
    {
        $this->cachedRequest->clearCache();
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array(),
            "up_json"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 401);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json?api_key=BADKEY",
            array(),
            "up_json"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 401);
    }
     /**
      * GET /people_groups/daily_unreached.json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldGetDailyUnreachedInJSON()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "up_json"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertTrue(isJSON($response));
    }
     /**
      * GET /people_groups/daily_unreached.xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldGetDailyUnreachedInXML()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.xml",
            array('api_key' => $this->APIKey),
            "up_xml"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertTrue(isXML($response));
    }
    /**
     * A request for Daily Unreached should allow setting the month
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetMonth()
    {
        $expectedMonth = '5';
        $expectedDay = Date('j');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'month' => $expectedMonth),
            "up_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }
    /**
     * A request for Daily Unreached should allow setting the day
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetDay()
    {
        $expectedMonth = Date('n');
        $expectedDay = '23';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'day' => $expectedDay),
            "up_day"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }
    /**
     * A request for Daily Unreached should allow setting the day and month
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetDayAndMonth()
    {
        $expectedMonth = '3';
        $expectedDay = '21';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'day' => $expectedDay, 'month' => $expectedMonth),
            "up_day_and_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }
     /**
      * GET /people_groups/[ID].json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowActionShouldGive404IfNoValidId()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/a.json",
            array('api_key' => $this->APIKey),
            "wrong_id_request"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 404);
        $this->assertTrue(isJSON($response));
    }
     /**
      * GET /people_groups/[ID].json?country=CB
      * test page is available, and delivers the correct People Group
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldGetCorrectPeopleGroupFromShowWhenIDAndCountryProvided()
    {
        $expectedID = "12662";
        $expectedCountry = "CB";
        $expectedName = "Khmer, Central";
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/12662.json",
            array('api_key' => $this->APIKey, 'country' => 'CB'),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertEquals($expectedID, $decodedResponse[0]['PeopleID3']);
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ROG3']);
        $this->assertEquals($expectedName, $decodedResponse[0]['PeopNameInCountry']);
    }
    /**
     * GET /people_groups/[ID].json
     * test page is available, and delivers the correct number of people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetCorrectPeopleGroupsFromShowWhenIDProvided()
    {
        $expectedID = "12662";
        $expectedPeopleGroups = 10;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/12662.json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertEquals($expectedID, $decodedResponse[0]['PeopleID3']);
        $this->assertEquals($expectedPeopleGroups, count($decodedResponse));
    }
    /**
     * GET /people_groups/[ID].json
     * test page returns a 404 error because the PeopleGroup by that ID does not exists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldProvide404ErrorIfTheIDDoesNotExist()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/2292828272736363511516.json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 404);
    }
    /**
     * GET /people_groups.json
     * test page returns all the people groups if no filters are applied
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnAllPeopleGroupsOnIndexWithNoFilters()
    {
        $pgCountQuery = $this->db->prepare("SELECT COUNT(*) FROM jppeoples");
        $pgCountQuery->execute(array());
        $rows = $pgCountQuery->fetch(\PDO::FETCH_NUM);
        $expectedNumberOfResults = intval($rows[0]);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
            array('api_key' => $this->APIKey),
            "all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertEquals(count($decodedResponse), $expectedNumberOfResults);
    }
    /**
     * gets an APIKey by sending a request to the /api_keys url
     *
     * @return string
     * @author Johnathan Pulos
     **/
    private function setAPIKey()
    {
        if ($this->APIKey == "") {
            $this->cachedRequest->post(
                "http://joshua.api.local/api_keys",
                array('name' => 'people_groups_test', 'email' => 'joe@people_groups.com', 'usage' => 'testing'),
                "people_groups_api"
            );
            $lastVisitedURL = $this->cachedRequest->lastVisitedURL;
            $APIKeyCheck = preg_match('/api_key=(.*)/', $lastVisitedURL, $matches);
            if (isset($matches[1])) {
                $this->APIKey = $matches[1];
            } else {
                echo "Unable to set the API Key!";
                die();
            }
        }
    }
}
