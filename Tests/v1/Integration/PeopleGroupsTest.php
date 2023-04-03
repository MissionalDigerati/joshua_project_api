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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace Tests\v1\Integration;

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
     * @var \PHPToolbox\CachedRequest\CachedRequest
     */
    public $cachedRequest;
    /**
     * The PDO database connection object
     *
     * @var \PHPToolbox\PDODatabase\PDODatabaseConnect
     */
    private $db;
    /**
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private $APIKey = '';
    /**
     * The current API version number
     *
     * @var string
     * @access private
     **/
    private $APIVersion;
    /**
     * The URL for the testing server
     *
     * @var string
     * @access private
     **/
    private $siteURL;
    /**
     * Set up the test class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        global $API_VERSION;
        $this->APIVersion = $API_VERSION;
        global $SITE_URL;
        $this->siteURL = $SITE_URL;
        $this->cachedRequest = new \PHPToolbox\CachedRequest\CachedRequest;
        $this->cachedRequest->cacheDirectory =
            __DIR__ .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
        $this->db = getDatabaseInstance();
        $this->APIKey = createApiKey([]);
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
        deleteApiKey($this->APIKey);
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(),
            "up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "versioning_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
        $this->assertEquals(
            'You are requesting an unavailable API version number.',
            $decoded['api']['error']['details']
        );
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithoutActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "versioning_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "versioning_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json?api_key=BADKEY",
            array(),
            "up_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "up_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.xml",
            array('api_key' => $this->APIKey),
            "up_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'month' => $expectedMonth, 'day' => $expectedDay),
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
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
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'day' => $expectedDay, 'month' => $expectedMonth),
            "up_day_and_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }
    /**
     * A request for Daily Unreached should provide the ProfileText
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithProfileText()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "up_day_and_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(isset($decodedResponse[0]['ProfileText']));
        $this->assertTrue(is_array($decodedResponse[0]['ProfileText']));
        if (count($decodedResponse[0]['ProfileText']) > 0) {
            $this->assertEquals('M', $decodedResponse[0]['ProfileText'][0]['Format']);
        }
    }
     /**
      * GET /people_groups/[ID].json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldGive404IfNoValidId()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/a.json",
            array('api_key' => $this->APIKey),
            "wrong_id_request"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
        $this->assertEquals('You provided an invalid PeopleID3.', $decoded['api']['error']['details']);
    }
     /**
      * GET /people_groups/[ID].json?country=CB
      * test page is available, and delivers the correct People Group
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldGetCorrectPeopleGroup()
    {
        $expectedID = "12662";
        $expectedCountry = "CB";
        $expectedName = "Khmer";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
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
      * GET /people_groups/[ID].json?country=CB
      * test page is available, and delivers the People Group's ProfileText
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldGetPeopleGroupsWithProfileText()
    {
        $expectedID = "12662";
        $expectedCountry = "CB";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey, 'country' => 'CB'),
            "show_in_country_gets_profile_text_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(isset($decodedResponse[0]['ProfileText']));
        $this->assertTrue(is_array($decodedResponse[0]['ProfileText']));
    }
    /**
     * GET /people_groups/[ID].json?country=BA
     * test page is available, and delivers the PeopleGroups Resources
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldGetPeopleGroupsWithResources()
    {
        $expectedId = "10572";
        $expectedCountry = "BA";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedId . ".json",
            array('api_key' => $this->APIKey, 'country' => $expectedCountry),
            "show_in_country_gets_resources_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(isset($decodedResponse[0]['Resources']));
        $this->assertTrue(is_array($decodedResponse[0]['Resources']));
    }
    /**
      * GET /people_groups/[ID].json?country=CB
      * test page is available, and delivers the People Group's ProfileText When Only ID is Provided
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldGetPeopleGroupsWithProfileTextWhenIdsOnlyProvided()
    {
        $expectedID = "12662";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey),
            "show_get_proper_profile_text_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['ProfileText']));
            $this->assertTrue(is_array($peopleGroupData['ProfileText']));
        }
    }
    /**
     * GET /people_groups/[ID].json?country=BA
     * test page is available, and delivers the People Group's Resources when only an id is provided
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldGetPeopleGroupsWithResourcesWhenIdsOnlyProvided()
    {
        $expectedID = "10572";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedID . ".json",
            array('api_key' => $this->APIKey),
            "show_get_proper_resources_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['Resources']));
            $this->assertTrue(is_array($peopleGroupData['Resources']));
        }
    }
    /**
     * GET /people_groups/[ID].json
     * test page is available, and delivers the correct number of people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShowRequestsShouldGetCorrectPeopleGroupsWhenIdsOnlyProvided()
    {
        $expectedID = "12662";
        $expectedPeopleGroups = 13;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedID . ".json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
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
    public function testShowRequestsShould404ErrorIfIdDoesNotExist()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/2292828272736363511516.json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
     * GET /people_groups.json
     * test page returns all the people groups if no filters are applied
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturn100ByDefault()
    {
        $expectedNumberOfResults = 100;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertEquals($expectedNumberOfResults, count($decodedResponse));
    }
    /**
     * GET /people_groups.json
     * test page returns Profile Text for all the people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnProfileTextForAllPeopleGroups()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "profile_text_for_all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['ProfileText']));
            $this->assertTrue(is_array($peopleGroupData['ProfileText']));
        }
    }
    /**
     * GET /people_groups.json
     * test page returns Resources for all people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldReturnResourcesForAllPeopleGroups()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "resources_for_all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['Resources']));
            $this->assertTrue(is_array($peopleGroupData['Resources']));
        }
    }
    /**
     * GET /people_groups.json?people_id1=17
     * test page filters by people_id1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId1()
    {
        $expectedPeopleIds = array(17, 23);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id1' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID1']), $expectedPeopleIds));
        }
    }
    /**
     * GET /people_groups.json?rop1=A014
     * test page filters by ROP1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1()
    {
        $expectedROP = array('A014', 'A010');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop1' => join("|", $expectedROP)),
            "filter_by_rop_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP1'], $expectedROP));
        }
    }
    /**
     * GET /people_groups.json?rop1=A014&people_id=23
     * test page filters by ROP1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1AndPeopleID1()
    {
        $expectedROP = 'A014';
        $expectedPeopleID = 23;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop1' => $expectedROP, 'people_id1' => $expectedPeopleID),
            "filter_by_rop_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedROP, $peopleGroup['ROP1']);
            $this->assertEquals($expectedPeopleID, intval($peopleGroup['PeopleID1']));
        }
    }
    /**
     * GET /people_groups.json?people_id2=115
     * test page filters by people_id2
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId2()
    {
        $expectedPeopleIds = array(117, 115);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id2' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_2_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID2']), $expectedPeopleIds));
        }
    }
    /**
     * GET /people_groups.json?rop2=C0013
     * test page filters by ROP2
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP2()
    {
        $expectedROP = array('C0013', 'C0067');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop2' => join("|", $expectedROP)),
            "filter_by_rop_2_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP2'], $expectedROP));
        }
    }
    /**
     * GET /people_groups.json?people_id3=11722
     * test page filters by people_id3
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId3()
    {
        $expectedPeopleIds = array(11722, 19204);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id3' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_3_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID3']), $expectedPeopleIds));
        }
    }
    /**
     * GET /people_groups.json?rop3=115485
     * test page filters by ROP3
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP3()
    {
        $expectedROP = array(115485, 115409);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop3' => join("|", $expectedROP)),
            "filter_by_rop_3_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['ROP3']), $expectedROP));
        }
    }
    /**
     * GET /people_groups.json?continents=afr|nar
     * test page filters by continents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByContinents()
    {
        $expectedCountries = array('AFR', 'NAR');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'continents' => join("|", $expectedCountries)),
            "filter_by_continents_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG2'], $expectedCountries));
        }
    }
    /**
     * GET /people_groups.json?regions=3|4
     * test page filters by regions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByRegions()
    {
        $expectedRegions = array(3 => 'asia, northeast', 4 => 'asia, south');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'regions' => join("|", array_keys($expectedRegions))),
            "filter_by_regions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['RegionCode']), array_keys($expectedRegions)));
            $this->assertTrue(in_array(strtolower($peopleGroup['RegionName']), array_values($expectedRegions)));
        }
    }
    /**
     * GET /people_groups.json?countries=an|bg
     * test page filters by countries
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByCountries()
    {
        $expectedCountries = array('AN', 'BG');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'countries' => join("|", $expectedCountries)),
            "filter_by_countries_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG3'], $expectedCountries));
        }
    }
    /**
     * GET /people_groups.json?window1040=y
     * test page filters by window1040
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredBy1040Window()
    {
        $expected1040Window = 'Y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'window1040' => $expected1040Window),
            "filter_by_1040_window_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expected1040Window, $peopleGroup['Window1040']);
        }
    }
    /**
     * GET /people_groups.json?languages=aka|ale
     * test page filters by languages
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLanguages()
    {
        $expectedLanguages = array('AKA', 'ALE');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'languages' => join("|", $expectedLanguages)),
            "filter_by_languages_aka_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(strtoupper($peopleGroup['ROL3']), $expectedLanguages));
        }
    }
    /**
     * GET /people_groups.json?population=10000-20000
     * test page filters by a set range of population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByAMinAndMaxPopulation()
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population' => $expectedMin."-".$expectedMax),
            "filter_by_pop_in_range_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedMax, intval($peopleGroup['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($peopleGroup['Population']));
        }
    }
    /**
     * GET /people_groups.json?population=10000
     * test page filters by a single population number
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByASetPopulation()
    {
        $expectedPop = 156000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population' => $expectedPop),
            "filter_by_set_pop_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedPop, intval($peopleGroup['Population']));
        }
    }
    /**
     * GET /people_groups.json?primary_religions=3|6
     * test page filters by primary religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPrimaryReligions()
    {
        $expectedReligions = array(2 => 'buddhism');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_primary_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(strtolower($peopleGroup['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($peopleGroup['RLG3'], array_keys($expectedReligions)));
        }
    }
    /**
     * GET /people_groups.json?pc_adherent=1.0-6.9
     * test page filters by percentage of adherents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfAdherents()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 6.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_adherent' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_adherents_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentAdherents']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentAdherents']));
        }
    }
    /**
     * GET /people_groups.json?pc_evangelical=10.0-20.8
     * test page filters by percentage of evangelicals
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEvangelicals()
    {
        $expectedPercentMin = 10.0;
        $expectedPercentMax = 20.8;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_evangelical' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_evangelicals_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentEvangelical']));
        }
    }
    /**
     * GET /people_groups.json?pc_buddhist=30.0-40.9
     * test page filters by percentage of buddhists
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfBuddhists()
    {
        $expectedPercentMin = 30.0;
        $expectedPercentMax = 40.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_buddhist' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_buddhist_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCBuddhism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCBuddhism']));
        }
    }
    /**
     * GET /people_groups.json?pc_ethnic_religion=1.0-3.9
     * test page filters by percentage of ethnic religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEthnicReligions()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 3.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_ethnic_religion' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_ethnic_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCEthnicReligions']));
        }
    }
    /**
     * GET /people_groups.json?pc_hindu=1.0-30.2
     * test page filters by percentage of Hinduism
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfHindus()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 30.2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_hindu' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_hindus_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCHinduism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCHinduism']));
        }
    }
    /**
     * GET /people_groups.json?pc_islam=30.12-40.3
     * test page filters by percentage of Islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfIslam()
    {
        $expectedPercentMin = 30.12;
        $expectedPercentMax = 40.3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_islam' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_islam_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCIslam']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCIslam']));
        }
    }
    /**
     * GET /people_groups.json?pc_non_religious=40.12-55.3
     * test page filters by percentage of Non Religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfNonReligious()
    {
        $expectedPercentMin = 40.12;
        $expectedPercentMax = 55.3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_non_religious' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_non_religious_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCNonReligious']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCNonReligious']));
        }
    }
    /**
     * GET /people_groups.json?pc_other_religion=3.2-12.6
     * test page filters by percentage of Other Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfOtherReligions()
    {
        $expectedPercentMin = 3.2;
        $expectedPercentMax = 12.6;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_other_religion' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_other_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCOtherSmall']));
        }
    }
    /**
     * GET /people_groups.json?pc_unknown=3.22-35.67
     * test page filters by percentage of Unknown Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfUnknownReligions()
    {
        $expectedPercentMin = 3.22;
        $expectedPercentMax = 35.67;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_unknown' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_unknown_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCUnknown']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCUnknown']));
        }
    }
    /**
     * GET /people_groups.json?pc_anglican=8.7-40.1
     * test page no longer supports percentage of Anglicans
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfAnglicans()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_anglican' => "8.7-40.1"),
            "filter_by_percent_anglican_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_anglican',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?pc_independent=3.45-90.1
     * test page no longer supports percentage of Independents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfIndependents()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_independent' => "8.7-40.1"),
            "filter_by_percent_independent_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_independent',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?pc_protestant=55.5-87.77
     * test page no longer supports percentage of Protestants
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfProtestants()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_protestant' => "55.5-87.7"),
            "filter_by_percent_protestant_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_protestant',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?pc_orthodox=31.4-56.7
     * test page no longer supports percentage of Orthodox
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOrthodox()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_orthodox' => "31.4-56.7"),
            "filter_by_percent_orthodox_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_orthodox',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?pc_rcatholic=2.34-56.7
     * test page no longer supports percentage of Roman Catholic
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfRomanCatholic()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_rcatholic' => "2.34-56.7"),
            "filter_by_percent_orthodox_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_rcatholic',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?pc_other_christian=1.1-27.2
     * test page no longer supports percentage of Other Christians
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOtherChristian()
    {
        $expectedPercentMin = 1.1;
        $expectedPercentMax = 27.2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_other_christian' => "1.1-27.2"),
            "filter_by_percent_other_christians_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_other_christian',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /people_groups.json?jpscale=1.1|2.2|3.1
     * test page filters by JPScale
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByJPScale()
    {
        $expectedJPScales = "1|2|3";
        $expectedJPScalesArray = array(1, 2, 3);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'jpscale' => $expectedJPScales),
            "filter_by_jp_scale_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(floatval($peopleGroup['JPScale']), $expectedJPScalesArray));
        }
    }
    /**
     * GET /people_groups.json?indigenous=y
     * test page filters out non indigenous people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByIndigenousStatus()
    {
        $expectedIndigenousStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'indigenous' => $expectedIndigenousStatus),
            "filter_by_indigenous_status_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedIndigenousStatus, strtolower($peopleGroup['IndigenousCode']));
        }
    }
    /**
     * GET /people_groups.json?least_reached=y
     * test page filters out non least reached people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLeastReached()
    {
        $expectedLeastReachedStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'least_reached' => $expectedLeastReachedStatus),
            "filter_by_least_reached_status_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedLeastReachedStatus, strtolower($peopleGroup['LeastReached']));
        }
    }
    /**
     * GET /people_groups.json?unengaged=y
     * test page filters out non unengaged people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByUnengaged()
    {
        $expectedUnengagedStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'unengaged' => $expectedUnengagedStatus),
            "filter_by_unengaged_status_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedUnengagedStatus, strtolower($peopleGroup['Unengaged']));
        }
    }
}
