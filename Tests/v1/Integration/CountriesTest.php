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
     * GET /countries/usa.json
     * Country Show should return population among the unreached
     * and the frontier people groups.
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShowRequestsShouldReturnPopulationByGroupStatus()
    {
        $expectedCountry = "AE";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/" . $expectedCountry . ".json",
            array('api_key' => $this->APIKey),
            "should_return_country_pop_status_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ISO2']);
        $this->assertTrue(array_key_exists('PoplPeoplesLR', $decodedResponse[0]));
        $this->assertTrue(array_key_exists('PoplPeoplesFPG', $decodedResponse[0]));
        $this->assertFalse(empty($decodedResponse[0]['PoplPeoplesLR']));
        $this->assertFalse(empty($decodedResponse[0]['PoplPeoplesFPG']));
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
        // 250 is max, but there is only 238 countries
        $expectedCountryCount = 238;
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
     * GET /countries.json
     * Country Index should return population among the unreached
     * and the frontier people groups.
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldReturnPopulationByGroupStatus()
    {
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
        $first = $decodedResponse[0];
        $this->assertTrue(array_key_exists('PoplPeoplesLR', $first));
        $this->assertTrue(array_key_exists('PoplPeoplesFPG', $first));
        $this->assertFalse(empty($first['PoplPeoplesLR']));
        $this->assertFalse(empty($first['PoplPeoplesFPG']));
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
    /**
     * GET /countries.json
     * test page no longer provides removed outdated columns
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testIndexRequestsShouldNotReturnRemovedColumns()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey),
            "no_removed_fields_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('HDIYear', $decoded[0]));
        $this->assertFalse(array_key_exists('HDIRank', $decoded[0]));
        $this->assertFalse(array_key_exists('HDIValue', $decoded[0]));
        $this->assertFalse(array_key_exists('WINCountryProfile', $decoded[0]));
        $this->assertFalse(array_key_exists('LiteracyRate', $decoded[0]));
        $this->assertFalse(array_key_exists('LiteracySource', $decoded[0]));
        $this->assertFalse(array_key_exists('AltName', $decoded[0]));
        $this->assertFalse(array_key_exists('AreaSquareMiles', $decoded[0]));
        $this->assertFalse(array_key_exists('InternetCtryCode', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentBuddhism', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentChristianity', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentEthnicReligions', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentEvangelical', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentHinduism', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentIslam', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentNonReligious', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentOtherSmall', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentUnknown', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentUrbanized', $decoded[0]));
        $this->assertFalse(array_key_exists('PoplGrowthRate', $decoded[0]));
        $this->assertFalse(array_key_exists('PrayercastVideo', $decoded[0]));
        $this->assertFalse(array_key_exists('ReligionDataYear', $decoded[0]));
        $this->assertFalse(array_key_exists('RLG4Primary', $decoded[0]));
        $this->assertFalse(array_key_exists('StateDeptReligiousFreedom', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
    }
    /**
     * GET /countries/ID.json
     * test page no longer provides removed outdated columns
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function testCountryShowRequestsShouldNotReturnRemovedColumns()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/US.json",
            array('api_key' => $this->APIKey),
            "no_removed_fields_country_show_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('HDIYear', $decoded[0]));
        $this->assertFalse(array_key_exists('HDIRank', $decoded[0]));
        $this->assertFalse(array_key_exists('HDIValue', $decoded[0]));
        $this->assertFalse(array_key_exists('WINCountryProfile', $decoded[0]));
        $this->assertFalse(array_key_exists('LiteracyRate', $decoded[0]));
        $this->assertFalse(array_key_exists('LiteracySource', $decoded[0]));

        $this->assertFalse(array_key_exists('AltName', $decoded[0]));
        $this->assertFalse(array_key_exists('AreaSquareMiles', $decoded[0]));
        $this->assertFalse(array_key_exists('InternetCtryCode', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentBuddhism', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentChristianity', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentEthnicReligions', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentEvangelical', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentHinduism', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentIslam', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentNonReligious', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentOtherSmall', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentUnknown', $decoded[0]));
        $this->assertFalse(array_key_exists('PercentUrbanized', $decoded[0]));
        $this->assertFalse(array_key_exists('PoplGrowthRate', $decoded[0]));
        $this->assertFalse(array_key_exists('PrayercastVideo', $decoded[0]));
        $this->assertFalse(array_key_exists('ReligionDataYear', $decoded[0]));
        $this->assertFalse(array_key_exists('RLG4Primary', $decoded[0]));
        $this->assertFalse(array_key_exists('StateDeptReligiousFreedom', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
    }

    public function testCountryIndexRequestsShouldProvideNewFields()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array('api_key' => $this->APIKey, 'limit' => 1),
            "provide_new_fields_country_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('CntPrimaryLanguages', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationUnspecified', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationNeeded', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationStarted', $decoded[0]));
        $this->assertTrue(array_key_exists('BiblePortions', $decoded[0]));
        $this->assertTrue(array_key_exists('BibleNewTestament', $decoded[0]));
        $this->assertTrue(array_key_exists('BibleComplete', $decoded[0]));
    }

    public function testCountryShowRequestsShouldProvideNewFields()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries/AF.json",
            array('api_key' => $this->APIKey),
            "provide_new_fields_country_show_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('CntPrimaryLanguages', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationUnspecified', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationNeeded', $decoded[0]));
        $this->assertTrue(array_key_exists('TranslationStarted', $decoded[0]));
        $this->assertTrue(array_key_exists('BiblePortions', $decoded[0]));
        $this->assertTrue(array_key_exists('BibleNewTestament', $decoded[0]));
        $this->assertTrue(array_key_exists('BibleComplete', $decoded[0]));
        $this->assertEquals(53, $decoded[0]['CntPrimaryLanguages']);
        $this->assertEquals(22, $decoded[0]['TranslationUnspecified']);
        $this->assertEquals(2, $decoded[0]['TranslationNeeded']);
        $this->assertEquals(0, $decoded[0]['TranslationStarted']);
        $this->assertEquals(10, $decoded[0]['BiblePortions']);
        $this->assertEquals(2, $decoded[0]['BibleNewTestament']);
        $this->assertEquals(17, $decoded[0]['BibleComplete']);
    }

    public function testCountryIndexRequestsShouldFilterByCntPrimaryLanguagesInRange()
    {
        $min = 2;
        $max = 4;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'cnt_primary_languages' => $min . '-' . $max,
            ),
            "filter_by_cnt_languages_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['CntPrimaryLanguages']));
            $this->assertGreaterThanOrEqual($min, floatval($country['CntPrimaryLanguages']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByCntPrimaryLanguagesAtValue()
    {
        $value = 3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'cnt_primary_languages' => $value,
            ),
            "filter_by_cnt_languages_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['CntPrimaryLanguages']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationUnspecifiedInRange()
    {
        $min = 1;
        $max = 2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_unspecified' => $min . '-' . $max,
            ),
            "filter_by_translation_unspecified_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['TranslationUnspecified']));
            $this->assertGreaterThanOrEqual($min, floatval($country['TranslationUnspecified']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationUnspecifiedAtValue()
    {
        $value = 3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_unspecified' => $value,
            ),
            "filter_by_translation_unspecified_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['TranslationUnspecified']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationNeededInRange()
    {
        $min = 1;
        $max = 2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_needed' => $min . '-' . $max,
            ),
            "filter_by_translation_needed_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['TranslationNeeded']));
            $this->assertGreaterThanOrEqual($min, floatval($country['TranslationNeeded']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationNeededAtValue()
    {
        $value = 3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_needed' => $value,
            ),
            "filter_by_translation_needed_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['TranslationNeeded']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationStartedInRange()
    {
        $min = 4;
        $max = 5;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_started' => $min . '-' . $max,
            ),
            "filter_by_translation_started_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['TranslationStarted']));
            $this->assertGreaterThanOrEqual($min, floatval($country['TranslationStarted']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByTranslationStartedAtValue()
    {
        $value = 1;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'translation_started' => $value,
            ),
            "filter_by_translation_started_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['TranslationStarted']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBiblePortionsInRange()
    {
        $min = 3;
        $max = 5;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_portions' => $min . '-' . $max,
            ),
            "filter_by_bible_portions_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['BiblePortions']));
            $this->assertGreaterThanOrEqual($min, floatval($country['BiblePortions']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBiblePortionsAtValue()
    {
        $value = 0;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_portions' => $value,
            ),
            "filter_by_bible_portions_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['BiblePortions']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBibleNewTestamentInRange()
    {
        $min = 1;
        $max = 2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_new_testament' => $min . '-' . $max,
            ),
            "filter_by_bible_new_testament_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['BibleNewTestament']));
            $this->assertGreaterThanOrEqual($min, floatval($country['BibleNewTestament']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBibleNewTestamentAtValue()
    {
        $value = 1;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_new_testament' => $value,
            ),
            "filter_by_bible_new_testament_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['BibleNewTestament']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBibleCompleteInRange()
    {
        $min = 1;
        $max = 2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_complete' => $min . '-' . $max,
            ),
            "filter_by_bible_complete_range_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertLessThanOrEqual($max, floatval($country['BibleComplete']));
            $this->assertGreaterThanOrEqual($min, floatval($country['BibleComplete']));
        }
    }

    public function testCountryIndexRequestsShouldFilterByBibleCompleteAtValue()
    {
        $value = 10;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/countries.json",
            array(
                'api_key' => $this->APIKey,
                'limit' => 5,
                'bible_complete' => $value,
            ),
            "filter_by_bible_complete_at_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $country) {
            $this->assertEquals($value, floatval($country['BibleComplete']));
        }
    }
}
