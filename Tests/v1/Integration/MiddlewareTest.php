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

use \PHPToolbox\CachedRequest\CachedRequest;
use \PHPToolbox\PDODatabase\PDODatabaseConnect;

/**
 * The class for testing middleware integration
 *
 * @author Johnathan Pulos
 */
class MiddlewareTest extends \PHPUnit_Framework_TestCase
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
        $this->cachedRequest = new CachedRequest();
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

    public function testItShouldRecordTheLastRequestDateOnEveryRequest()
    {
        $this->db->query("UPDATE `md_api_keys` SET last_request = '2012-10-21 10:05:00' WHERE  `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/asi.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $query = $this->db->query("SELECT * FROM `md_api_keys` WHERE  `api_key` = '" . $this->APIKey . "'");
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertFalse(empty($data[0]['last_request']));
        $this->assertNotEquals('2012-10-21 10:05:00', $data[0]['last_request']);
    }
}
