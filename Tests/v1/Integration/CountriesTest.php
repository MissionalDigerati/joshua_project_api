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
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace Tests\v1\Integration;

/**
 * The class for testing integration of the Countries
 *
 * @author Johnathan Pulos
 */
class CountriesTest extends \PHPUnit_Framework_TestCase
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
        $this->APIKey = createApiKey();
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
     * Tests that you get a 404 Error if you do not pass an id to the country -> show action
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessWithoutAValidId()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/1234.json",
            array('api_key' => $this->APIKey),
            "country_show_without_id"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertEquals('You provided an invalid country id.', $decoded['api']['error']['details']);
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
        $this->assertTrue(isJSON($response));
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/countries/us.json",
            array('api_key' => $this->APIKey),
            "versioning_missing_countries_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertEquals(
            'You are requesting an unavailable API version number.',
            $decoded['api']['error']['details']
        );
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(),
            "index_country_up_test_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertEquals('You are missing your API key.', $decoded['api']['error']['details']);
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
    }
    /**
      * GET /countries/usa.json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnACountryInJSON()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/" . $expectedCountry . ".json",
            array('api_key' => $this->APIKey),
            "should_return_country_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /countries/usa.xml
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnACountryInXML()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/" . $expectedCountry . ".xml",
            array('api_key' => $this->APIKey),
            "should_return_country_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
      * GET /countries/usa.json
      * Country Show should return the correct country data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnTheCorrectCountry()
    {
        $expectedCountry = "US";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/" . $expectedCountry . ".json",
            array('api_key' => $this->APIKey),
            "should_return_country_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ISO2']);
    }
    /**
      * GET /countries.json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestShouldBeAccessibleByJSON()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey),
            "should_return_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /countries.xml
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestShouldBeAccessableByXML()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.xml",
            array('api_key' => $this->APIKey),
            "should_return_country_index_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
      * GET /countries.json
      * Country Index should return the correct data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnTheCorrectCountry()
    {
        $expectedCountryCount = 100;
        $expectedFirstCountry = 'Afghanistan';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey),
            "should_return_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedCountryCount, count($decodedResponse));
        $this->assertEquals($expectedFirstCountry, $decodedResponse[0]['Ctry']);
    }
    /**
      * GET /countries.json?limit=10
      * Country Index should return the correct data with a limit
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesLimitedToOurRequest()
    {
        $expectedCountryCount = 10;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'limit' => $expectedCountryCount),
            "should_return_country_index_with_limit_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedCountryCount, count($decodedResponse));
    }
    /**
      * GET /countries.json?ids=US|AF|AL
      * Country Index should return the correct data when setting the ids parameter
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesFilteredByIds()
    {
        $expectedIDs = array('us', 'af', 'al');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'ids' => join('|', $expectedIDs)),
            "should_return_country_index_with_ids_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROG3']), $expectedIDs));
        }
    }
    /**
      * GET /countries.json?continents=EUR|NAR
      * Country Index should return the correct data when filtering by continents
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesFilteredByContinents()
    {
        $expectedContinents = array('eur', 'nar');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'continents' => join('|', $expectedContinents)),
            "should_return_country_index_with_continents_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROG2']), $expectedContinents));
        }
    }
    /**
      * GET /countries.json?regions=1|5
      * Country Index should return the correct data when filtering by regions
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesFilteredByRegions()
    {
        $expectedRegions = array(1, 5);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'regions' => join('|', $expectedRegions)),
            "should_return_country_index_with_regions_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['RegionCode']), $expectedRegions));
        }
    }
    /**
      * GET /countries.json?window1040=n
      * Country Index should return the correct data when filtering by window1040
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesFilteredByWindow1040()
    {
        $expectedWindow1040 = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'window1040' => $expectedWindow1040),
            "should_return_country_index_with_window_1040_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertEquals(strtolower($country['Window1040']), $expectedWindow1040);
        }
    }
    /**
      * GET /countries.json?primary_languages=por
      * Country Index should return the correct data when filtering by primary_languages
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnCountriesFilteredByPrimaryLanguages()
    {
        $expectedPrimaryLanguages = array('por');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'primary_languages' => join('|', $expectedPrimaryLanguages)),
            "should_return_country_index_with_primary_languages_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $country) {
            $this->assertTrue(in_array(strtolower($country['ROL3OfficialLanguage']), $expectedPrimaryLanguages));
        }
    }
    /**
     * GET /countries.json?population=10000-20000
     * test page filters by a set range of population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsReturnCountriesFilteredByAMinAndMaxPopulation()
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'population' => $expectedMin."-".$expectedMax),
            "filter_by_pop_in_range_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $country) {
            $this->assertLessThanOrEqual($expectedMax, intval($country['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($country['Population']));
        }
    }
    /**
     * GET /countries.json?population=600
     * test page filters by an exact population
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsReturnCountriesFilteredByAnExactPopulation()
    {
        $expectedPopulation = 600;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'population' => $expectedPopulation),
            "filter_by_pop_exact_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $country) {
            $this->assertEquals($expectedPopulation, intval($country['Population']));
        }
    }
    /**
     * GET /countries.json?primary_religions=1|7
     * test page filters by primary religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByPrimaryReligions()
    {
        $expectedReligions = array(1 => 'christianity', 7 => 'non-religious');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_primary_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ReligionPrimary']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3Primary'], array_keys($expectedReligions)));
        }
    }
    /**
     * GET /countries.json?primary_religions=7
     * test page filters by an exact primary religion
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByASinglePrimaryReligion()
    {
        $expectedReligions = array(7 => 'non-religious');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_exact_primary_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertTrue(in_array(strtolower($countryData['ReligionPrimary']), array_values($expectedReligions)));
            $this->assertTrue(in_array($countryData['RLG3Primary'], array_keys($expectedReligions)));
        }
    }
    /**
     * GET /countries.json?pc_christianity=10-20
     * test page filters by a range of percentage of christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCChristianity()
    {
        $expectedMin = 10;
        $expectedMax = 20;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_christianity' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_percent_christianity_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentChristianity']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentChristianity']));
        }
    }
    /**
     * GET /countries.json?pc_evangelical=0-20
     * test page filters by a range of percentage of christianity
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCEvangelical()
    {
        $expectedMin = 0;
        $expectedMax = 20;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_evangelical' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_percent_evangelical_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEvangelical']));
        }
    }
    /**
     * GET /countries.json?pc_buddhist=10-25
     * test page filters by a range of percentage of buddhist
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCBuddhist()
    {
        $expectedMin = 10;
        $expectedMax = 25;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_buddhist' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_buddhist_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentBuddhism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentBuddhism']));
        }
    }
    /**
     * GET /countries.json?pc_ethnic_religion=1-10
     * test page filters by a range of percentage of ethnic religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCEthnicReligions()
    {
        $expectedMin = 1;
        $expectedMax = 10;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_ethnic_religion' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_ethnic_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentEthnicReligions']));
        }
    }
    /**
     * GET /countries.json?pc_hindu=15-35
     * test page filters by a range of percentage of hindu
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCHindu()
    {
        $expectedMin = 15;
        $expectedMax = 35;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_hindu' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_hindu_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentHinduism']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentHinduism']));
        }
    }
    /**
     * GET /countries.json?pc_islam=85-100
     * test page filters by a range of percentage of Islam
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCIslam()
    {
        $expectedMin = 85;
        $expectedMax = 100;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_islam' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_islam_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentIslam']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentIslam']));
        }
    }
    /**
     * GET /countries.json?pc_non_religious=0-10
     * test page filters by a range of percentage of Non Religious
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCNonReligious()
    {
        $expectedMin = 0;
        $expectedMax = 10;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_non_religious' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_non_religious_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentNonReligious']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentNonReligious']));
        }
    }
    /**
     * GET /countries.json?pc_other_religion=2-3
     * test page filters by a range of percentage of Other Religions
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCOtherReligions()
    {
        $expectedMin = 2;
        $expectedMax = 3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_other_religion' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_other_religion_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentOtherSmall']));
        }
    }
    /**
     * GET /countries.json?jpscale=2.2
     * test page filters by JPScale
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByJPScale()
    {
        $expectedJPScale = "2";
        $expectedJPScalesArray = array(2);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'jpscale' => $expectedJPScale),
            "filter_by_jpscale_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertTrue(in_array(floatval($countryData['JPScaleCtry']), $expectedJPScalesArray));
        }
    }
    /**
     * GET /countries.json?pc_unknown=0-0.14
     * test page filters by a range of percentage of Unknown
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnCountriesFilteredByRangeOfPCUnknown()
    {
        $expectedMin = 0;
        $expectedMax = 0.14;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_unknown' => $expectedMin . '-' . $expectedMax),
            "filter_by_range_pc_unknown_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $countryData) {
            $this->assertLessThanOrEqual($expectedMax, floatval($countryData['PercentUnknown']));
            $this->assertGreaterThanOrEqual($expectedMin, floatval($countryData['PercentUnknown']));
        }
    }
    /**
     * GET /countries.json?pc_anglicans=20-25
     * test page api no longer supports percentage of Anglicals
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCAnglicanForCountries()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_anglican' => '20-25'),
            "filter_by_range_pc_anglican_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_anglican',
            $decodedResponse['api']['error']['details']
        );
    }
    /**
     * GET /countries.json?pc_independent=20-25
     * test page no longer supports percentage of Independents
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCIndependentForCountries()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_independent' => '20-25'),
            "filter_by_range_pc_independent_on_index_json"
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
     * GET /countries.json?pc_protestant=10-15
     * test page no longer supports percentage of Protestant
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCProtestantForCountries()
    {
        $expectedMin = 10;
        $expectedMax = 15;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_protestant' => '10-15'),
            "filter_by_range_pc_protestant_on_index_json"
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
     * GET /countries.json?pc_orthodox=70-74
     * test page no longer supports percentage of Orthodox
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCOrthodoxForCountries()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_orthodox' => '70-74'),
            "filter_by_range_pc_orthodox_on_index_json"
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
     * GET /countries.json?pc_rcatholic=20-25
     * test page no longer supports percentage of Roman Catholic
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCRomanCatholicForCountries()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_rcatholic' => '20-25'),
            "filter_by_range_pc_rcatholic_on_index_json"
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
     * GET /countries.json?pc_other_christians=11-14
     * test no longer supports percentage of Other Christians
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnUnsupportedPCOtherChristiansForCountries()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'pc_other_christian' => '11-14'),
            "filter_by_range_pc_other_christian_on_index_json"
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
}
