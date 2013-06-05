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
        $this->assertEquals(401, $this->cachedRequest->responseCode);
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
        $this->assertEquals(401, $this->cachedRequest->responseCode);
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
            "http://joshua.api.local/people_groups/daily_unreached.xml",
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
        $this->assertEquals(404, $this->cachedRequest->responseCode);
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
    public function testShouldProvide404ErrorIfTheIDDoesNotExist()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/2292828272736363511516.json",
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
    public function testIndexShouldReturn100PeopleGroupsOnIndexWithNoFiltersByDefault()
    {
        $expectedNumberOfResults = 100;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
            array('api_key' => $this->APIKey),
            "all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertEquals($expectedNumberOfResults, count($decodedResponse));
    }
    /**
     * GET /people_groups.json?people_id1=17
     * test page filters by people_id1
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexShouldReturnPeopleGroupsFilteredByPeopleId1()
    {
        $expectedPeopleIds = array(17, 23);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByROP1()
    {
        $expectedROP = array('A014', 'A010');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByROP1AndPeopleID1()
    {
        $expectedROP = 'A014';
        $expectedPeopleID = 23;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByPeopleId2()
    {
        $expectedPeopleIds = array(117, 115);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByROP2()
    {
        $expectedROP = array('C0013', 'C0067');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByPeopleId3()
    {
        $expectedPeopleIds = array(11722, 19204);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByROP3()
    {
        $expectedROP = array(115485, 115409);
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByContinents()
    {
        $expectedCountries = array('AFR', 'NAR');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByRegions()
    {
        $expectedRegions = array(3 => 'northeast asia', 4 => 'south asia');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredByCountries()
    {
        $expectedCountries = array('AN', 'BG');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFilteredBy1040Window()
    {
        $expected1040Window = 'Y';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
            array('api_key' => $this->APIKey, 'window1040' => $expected1040Window),
            "filter_by_1040_window_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expected1040Window, $peopleGroup['Window10_40']);
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
    public function testIndexShouldReturnPeopleGroupsFilteredByLanguages()
    {
        $expectedLanguages = array('AKA', 'ALE');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByAMinAndMaxPopulation()
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByASetPopulation()
    {
        $expectedPop = 19900;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPrimaryReligions()
    {
        $expectedReligions = array(2 => 'buddhism');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfAdherents()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 6.9;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfEvangelicals()
    {
        $expectedPercentMin = 10.0;
        $expectedPercentMax = 20.8;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfBuddhists()
    {
        $expectedPercentMin = 30.0;
        $expectedPercentMax = 40.9;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfEthnicReligions()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 3.9;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfHindus()
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 30.2;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
    public function testIndexShouldReturnPeopleGroupsFliteredByPercentageOfIslam()
    {
        $expectedPercentMin = 30.12;
        $expectedPercentMax = 40.3;
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups.json",
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
