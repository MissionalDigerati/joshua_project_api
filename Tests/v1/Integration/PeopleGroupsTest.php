<?php
declare(strict_types=1);

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

use PHPToolbox\CachedRequest\CachedRequest;
use PHPToolbox\PDODatabase\PDODatabaseConnect;
use PHPUnit\Framework\TestCase;

/**
 * The class for testing integration of the People Groups
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroupsTest extends TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var CachedRequest
     */
    public $cachedRequest;
    /**
     * The PDO database connection object
     *
     * @var PDODatabaseConnect
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
    public function setUp(): void
    {
        $this->APIVersion = $_ENV['api_version'];
        $this->siteURL = $_ENV['site_url'];
        $this->cachedRequest = new CachedRequest();
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
    public function tearDown(): void
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
    public function testShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(),
            "up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShouldRefuseAccessWithoutActiveAPIKey(): void
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
    public function testShouldRefuseAccessWithSuspendedAPIKey(): void
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
    public function testShouldRefuseAccessWithABadAPIKey(): void
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
    public function testShouldGetDailyUnreachedInJSON(): void
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
    public function testShouldGetDailyUnreachedInXML(): void
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
    public function testShouldGetDailyUnreachedWithSetMonth(): void
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
    public function testShouldGetDailyUnreachedWithSetDay(): void
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
    public function testShouldGetDailyUnreachedWithSetDayAndMonth(): void
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
      * GET /people_groups/[ID].json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldGive404IfNoValidId(): void
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
    public function testShowRequestsShouldGetCorrectPeopleGroup(): void
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
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedID, $decodedResponse[0]['PeopleID3']);
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ROG3']);
        $this->assertEquals($expectedName, $decodedResponse[0]['PeopNameInCountry']);
    }
    /**
     * GET /people_groups/[ID].json?country=BA
     * test page is available, and delivers the PeopleGroups Resources
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldGetPeopleGroupsWithResources(): void
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
     * GET /people_groups/[ID].json?country=BA
     * test page is available, and delivers the People Group's Resources when only an id is provided
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldGetPeopleGroupsWithResourcesWhenIdsOnlyProvided(): void
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
    public function testShowRequestsShouldGetCorrectPeopleGroupsWhenIdsOnlyProvided(): void
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
    public function testShowRequestsShould404ErrorIfIdDoesNotExist(): void
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
    public function testIndexRequestsShouldReturn250ByDefault(): void
    {
        $expectedNumberOfResults = 250;
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
     * test page returns Resources for all people groups
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldReturnResourcesForAllPeopleGroups(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId1(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1AndPeopleID1(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId2(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP2(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId3(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP3(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByContinents(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByRegions(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByCountries(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredBy1040Window(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLanguages(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByAMinAndMaxPopulation(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByASetPopulation(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPrimaryReligions(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfAdherents(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEvangelicals(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfBuddhists(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEthnicReligions(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfHindus(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfIslam(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfNonReligious(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfOtherReligions(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfUnknownReligions(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfAnglicans(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfIndependents(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfProtestants(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOrthodox(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfRomanCatholic(): void
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
    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOtherChristian(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByJPScale(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByIndigenousStatus(): void
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
    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLeastReached(): void
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
    public function testIndexRequestsShouldReturnUnsupportedUnengaged(): void
    {
        $expectedUnengagedStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'unengaged' => $expectedUnengagedStatus),
            "filter_by_unengaged_status_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded['api']));
        $this->assertEquals('error', $decoded['api']['status']);
        $this->assertFalse(empty($decoded['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: unengaged',
            $decoded['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByFrontier(): void
    {
        $expectedFrontier = "N";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'is_frontier' => $expectedFrontier),
            "filter_by_is_frontier_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($expectedFrontier, strtoupper($peopleGroup['Frontier']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPopulationPGACInRange(): void
    {
        $min = 120000;
        $max = 130000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population_pgac' => $min . '-' . $max),
            "filter_by_population_pgac_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertGreaterThanOrEqual($min, $peopleGroup['PopulationPGAC']);
            $this->assertLessThanOrEqual($max, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPopulationPGACAtValue(): void
    {
        $value = 120000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population_pgac' => $value),
            "filter_by_population_pgac_at_value_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($value, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testIndexRequestShouldReturnPeopleGroupsFilteredByBibleStatus(): void
    {
        $expected = [0, 5];
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'bible_status' => join('|', $expected)),
            "filter_by_bible_status_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertTrue(in_array((int) $peopleGroup["BibleStatus"], $expected));
        }
    }

    /**
     * GET: /people_groups/daily_unreached.json
     * test page no longer provides removed outdated fields
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testDailyUnreachedShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "unreached_no_old_fields"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }
    /**
     * GET /people_groups/[ID].json
     * test page no longer provides removed outdated fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShowRequestsShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey),
            "show_old_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }
    /**
     * GET /people_groups.json?unengaged=y
     * test page no longer provides removed outdated fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "index_old_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }
    /**
     * GET: /people_groups/daily_unreached.json
     * test page provides newly added fields
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testDailyUnreachedShouldProvideNewFields(): void
    {
        $expectedPop = 265000;
        $expectedFrontier = 'Y';
        $expectedMapUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m10252_gm.png';
        $expectedMapExpandedUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m10252_gm.pdf';
        $expectedPhotoCCVersionText = 'CC BY-SA 4.0';
        $expectedPhotoCCVersionURL = 'https://creativecommons.org/licenses/by-sa/4.0/';
        $expectedMapCredits = 'People Group location: Joshua Project, Map geography' .
        ': ESRI / GMI. Map design: Joshua Project.';
        $expectedMapCreditsURL = '';
        $expectedMapCopyright = 'N';
        $expectedMapCCVersionText = '';
        $expectedMapCCVersionURL = '';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'month' => '05', 'day'    =>  '31'),
            "unreached_no_new_fields"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
        $this->assertEquals($expectedFrontier, $decoded[0]['Frontier']);
        $this->assertEquals($expectedPop, $decoded[0]['PopulationPGAC']);
        $this->assertEquals($expectedMapUrl, $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals($expectedMapExpandedUrl, $decoded[0]['PeopleGroupMapExpandedURL']);
        $this->assertEquals($expectedPhotoCCVersionText, $decoded[0]['PhotoCCVersionText']);
        $this->assertEquals($expectedPhotoCCVersionURL, $decoded[0]['PhotoCCVersionURL']);
        $this->assertEquals($expectedMapCredits, $decoded[0]['MapCredits']);
        $this->assertEquals($expectedMapCreditsURL, $decoded[0]['MapCreditURL']);
        $this->assertEquals($expectedMapCopyright, $decoded[0]['MapCopyright']);
        $this->assertEquals($expectedMapCCVersionText, $decoded[0]['MapCCVersionText']);
        $this->assertEquals($expectedMapCCVersionURL, $decoded[0]['MapCCVersionURL']);
    }
    /**
     * GET /people_groups/[ID].json
     * test page provides newly added fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testShowRequestsShouldProvideNewFields(): void
    {
        $expectedPop = 1948100;
        $expectedFrontier = 'Y';
        $expectedMapAddress = 'm00324_aj.png';
        $expectedMapUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m00324_aj.png';
        $expectedMapExpandedUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m00324_aj.pdf';
        $expectedPhotoCCVersionText = 'CC BY-NC-SA 2.0';
        $expectedPhotoCCVersionURL = 'https://creativecommons.org/licenses/by-nc-sa/2.0/';
        $expectedMapCredits = 'Temo Blumgardt - Wikimedia';
        $expectedMapCreditsURL = 'https://commons.wikimedia.org/wiki/File:Caucasus_ethnic.jpg';
        $expectedMapCopyright = 'N';
        $expectedMapCCVersionText = 'CC0 1.0';
        $expectedMapCCVersionURL = 'https://creativecommons.org/publicdomain/zero/1.0/';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/11317.json",
            array('api_key' => $this->APIKey, 'country' =>  'AJ'),
            "show_new_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
        $this->assertEquals($expectedFrontier, $decoded[0]['Frontier']);
        $this->assertEquals($expectedPop, $decoded[0]['PopulationPGAC']);
        $this->assertEquals($expectedMapAddress, $decoded[0]['MapAddress']);
        $this->assertEquals($expectedMapUrl, $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals($expectedMapExpandedUrl, $decoded[0]['PeopleGroupMapExpandedURL']);
        $this->assertEquals($expectedPhotoCCVersionText, $decoded[0]['PhotoCCVersionText']);
        $this->assertEquals($expectedPhotoCCVersionURL, $decoded[0]['PhotoCCVersionURL']);
        $this->assertEquals($expectedMapCredits, $decoded[0]['MapCredits']);
        $this->assertEquals($expectedMapCreditsURL, $decoded[0]['MapCreditURL']);
        $this->assertEquals($expectedMapCopyright, $decoded[0]['MapCopyright']);
        $this->assertEquals($expectedMapCCVersionText, $decoded[0]['MapCCVersionText']);
        $this->assertEquals($expectedMapCCVersionURL, $decoded[0]['MapCCVersionURL']);
    }
    /**
     * GET /people_groups.json?unengaged=y
     * test page provides newly added fields
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldProvideNewFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "index_new_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
    }
    /**
     * GET /people_groups/[ID].json
     * test if map address is blank it does not send a bad url
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testMapAddressShouldSendEmptyURL(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey, 'country' =>  'US'),
            "show_map_address_send_empty_url_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertEquals('', $decoded[0]['MapAddress']);
        $this->assertEquals('', $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals('', $decoded[0]['PeopleGroupMapExpandedURL']);
    }
    /**
     * GET /people_groups/[ID].json
     * test if map address is blank it does not send a bad url
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testPeopleGroupPhotoURLShouldSendAnEmptyURLWhenPhotoAddressEmptyOrNull(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/10123.json",
            array('api_key' => $this->APIKey, 'country' =>  'PP'),
            "show_photo_url_send_empty_string_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('PeopleGroupPhotoURL', $decoded[0]));
        $this->assertEquals('', $decoded[0]['PeopleGroupPhotoURL']);
    }

    public function testUnreachedShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(
                'api_key'   => $this->APIKey,
                'month'     =>  '04',
                'day'       =>  '05'
            ),
            "unreached_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('ProfileText', $decoded[0]));
        $this->assertTrue(array_key_exists('Summary', $decoded[0]));
        $this->assertTrue(str_contains($decoded[0]['Summary'], 'Mongols in Inner Mongolia survive bitter winters'));
    }

    public function testShowShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/15642.json",
            array(
                'api_key'       => $this->APIKey,
                'country'       =>  'IS'
            ),
            "show_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('ProfileText', $decoded[0]));
        $this->assertTrue(array_key_exists('Summary', $decoded[0]));
        $this->assertTrue(str_contains($decoded[0]['Summary'], 'Though many Tunisian Jews remain in Tunisia'));
    }

    public function testIndexShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array(
                'api_key'       => $this->APIKey,
                'limit'         =>  5
            ),
            "index_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertFalse(array_key_exists('ProfileText', $pg));
            $this->assertTrue(array_key_exists('Summary', $pg));
        }
    }

    public function testUnreachedShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(
                'api_key'   => $this->APIKey,
                'month'     =>  '05',
                'day'       =>  '25'
            ),
            "unreached_with_prayer_details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertFalse(empty($pg['Obstacles']));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertFalse(empty($pg['HowReach']));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
            $this->assertFalse(empty($pg['PrayForPG']));
        }
    }

    public function testShowShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/16180.json",
            array(
                'api_key'       =>  $this->APIKey,
                'country'       =>  'IN'
            ),
            "show_provide_prayer_details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertFalse(empty($pg['Obstacles']));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertFalse(empty($pg['HowReach']));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
            $this->assertFalse(empty($pg['PrayForPG']));
        }
    }

    public function testIndexShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array(
                'api_key'       => $this->APIKey,
                'limit'         =>  5
            ),
            "index_provide_prayer-details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
        }
    }
}
