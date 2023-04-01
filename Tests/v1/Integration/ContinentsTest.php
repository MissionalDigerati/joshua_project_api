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
 * The class for testing integration of the Continents
 *
 * @author Johnathan Pulos
 */
class ContinentsTest extends \PHPUnit_Framework_TestCase
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
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private $APIKey = '';
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
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessWithoutAnAPIKey()
    {
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/4.json",
            array(),
            "continent_show_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessWithoutAVersionNumber()
    {
        $this->cachedRequest->get(
            $this->siteURL . "/continents/4.json",
            array('api_key' => $this->APIKey),
            "continents_versioning_missing_json"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessWithoutActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/3.json",
            array('api_key' => $this->APIKey),
            "non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessToSuspendedAPIKeys()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/3.json",
            array('api_key' => $this->APIKey),
            "suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestShouldRefuseAccessWithABadAPIKeys()
    {
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/1.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
      * GET /continents/[id].json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnContinentsInJSON()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/asi.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /continents/[id].xml
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnContinentsInXML()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/asi.xml",
            array('api_key' => $this->APIKey),
            "show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
     * GET /continents/[id].json
     * test the page throws an error if you send it an invalid id
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldThrowErrorIfIdIsBad()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/bad_id.json",
            array('api_key' => $this->APIKey),
            "show_with_bad_id"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
     * GET /continents/[id].json
     * test page returns the language requested
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRetrieveTheCorrectContinent()
    {
        $continentId = 'asi';
        $expectedContinent = 'asia';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/" . $continentId . ".json",
            array('api_key' => $this->APIKey),
            "show_returns_appropriate_continent"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedContinent, strtolower($decodedResponse[0]['Continent']));
    }
}
