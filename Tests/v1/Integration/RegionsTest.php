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
 * The class for testing integration of the Regions
 *
 * @package default
 * @author Johnathan Pulos
 */
class RegionsTest extends \PHPUnit_Framework_TestCase
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
    private $apiVersion;
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
        $this->apiVersion = $API_VERSION;
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
        $pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
        $pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
        $this->db = $pdoDb->getDatabaseInstance();
        $this->setAPIKey();
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
        /**
         * Clear all the api_keys generated by the test
         *
         * @author Johnathan Pulos
         */
        $this->db->query("DELETE FROM `md_api_keys` WHERE `api_usage` = 'testing'");
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->apiVersion . "/regions/2.json",
            array(),
            "region_show_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
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
            $this->siteURL . "/regions/4.json",
            array('api_key' => $this->APIKey),
            "regions_versioning_missing_json"
        );
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/regions/3.json",
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
    public function testShowRequestsShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/regions/2.json",
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
    public function testShowRequestsShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->apiVersion . "/regions/1.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
      * GET /regions/[id].json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnARegionInJSON()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->apiVersion . "/regions/3.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /regions/[id].xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnARegionInXML()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->apiVersion . "/regions/3.xml",
            array('api_key' => $this->APIKey),
            "show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
     * GET /regions/[id].json
     * test page returns the region requested
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRetrieveTheAppropriateRegion()
    {
        $regionId = 10;
        $expectedRegion = 'western europe';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->apiVersion . "/regions/" . $regionId . ".json",
            array('api_key' => $this->APIKey),
            "show_returns_appropriate_region"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($regionId, intval($decodedResponse[0]['RegionCode']));
        $this->assertEquals($expectedRegion, strtolower($decodedResponse[0]['RegionName']));
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
            $newAPIKey = generateRandomKey(12);
            $apiKeyValues = array(  'name' => 'Test API',
                                    'email' => 'joe@testing.com',
                                    'organization' => 'Testing.com',
                                    'website' => 'http://www.testing.com',
                                    'api_usage' => 'testing',
                                    'api_key' => $newAPIKey,
                                    'status' => 1
                                );
            /**
             * Create a new API Key
             *
             * @author Johnathan Pulos
             */
            $query = "INSERT INTO `md_api_keys` (name, email, organization, website, api_usage, api_key, status) 
                        VALUES (:name, :email, :organization, :website, :api_usage, :api_key, :status)";
            try {
                $statement = $this->db->prepare($query);
                $statement->execute($apiKeyValues);
                $this->APIKey = $newAPIKey;
            } catch (PDOException $e) {
                echo "Unable to set the API Key!";
                die();
            }
        }
    }
}
