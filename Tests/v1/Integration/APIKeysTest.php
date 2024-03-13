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
 * along with this program.  If not, see
 * <http://www.gnu.org/licenses/>.
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
 * The class for testing integration of the API Keys
 *
 * @author Johnathan Pulos
 */
class APIKeysTest extends TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var CachedRequest
     * @access public
     */
    public $cachedRequest;
    /**
     * The PDO database connection object
     *
     * @var PDODatabaseConnect
     * @access private
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
        $this->db->query("DELETE FROM `md_api_keys`");
    }

    /**
     * Tests that APIKey requests without all required fields redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetAllRequiredFieldsInURL(): void
    {
        $expectedURL = $this->siteURL . "/?required_fields=name|email|usage";
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            ['name' => '', 'email' => '', 'usage' => []],
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without name field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredNameFieldInURL(): void
    {
        $expectedURL = $this->siteURL . "/?required_fields=name&email=joe%40yahoo.com&usage=testing";
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => '', 'email' => 'joe@yahoo.com', 'usage' => ['testing']),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without email field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredEmailFieldInURL(): void
    {
        $expectedURL = $this->siteURL . "/?required_fields=email&name=joe&usage=testing";
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'joe', 'email' => '', 'usage' => ['testing']),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests without usage field redirects with the correct required_fields param
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestWithMissingPOSTParamsShouldSetRequiredUsageFieldInURL(): void
    {
        $expectedURL = $this->siteURL . "/?required_fields=usage&name=joe&email=joe%40yahoo.com";
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'joe', 'email' => 'joe@yahoo.com', 'usage' => []),
            "api_keys_required_fields"
        );
        $actualURL = $this->cachedRequest->lastVisitedURL;
        $this->assertEquals($expectedURL, $actualURL);
    }

    /**
     * Tests that APIKey requests with all fields returns an api_key
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldReturnIfAllPOSTParamsSupplied(): void
    {
        $name = 'testAPIKeyRequestShouldReturnIfAllPOSTParamsSupplied';
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => $name,
                'email' => 'joe@yahoo.com',
                'usage' => ['api development']
            ),
            "api_keys_required_fields"
        );
        $lastVisitedURL = $this->cachedRequest->lastVisitedURL;
        preg_match('/api_key=(.*)/', $lastVisitedURL, $matches);
        $this->assertFalse(empty($matches));
        $this->assertTrue(isset($matches[1]));
        $this->assertFalse($matches[1] == "");
    }

    /**
     * Tests that APIKey request should correctly store the usage information
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldCreateCorrectUsageString(): void
    {
        $name = "testAPIKeyRequestShouldCreateCorrectUsageString";
        $usage = ["Api development", "Testing", "Research project"];
        $expectedUsage = "api development,testing,research project";
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => $name,
                'email' => 'joe@yahoo.com',
                'usage' => $usage
            ),
            "api_key_request_correct_usage"
        );
        $statement = $this->db->query("SELECT api_usage from `md_api_keys` WHERE `name` = '" . $name . "'");
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedUsage, $data[0]['api_usage']);
    }

    /**
     * Tests that APIKey request should require website if For a website is in usage
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldRequireWebsiteIfForWebsiteUsage(): void
    {
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'require-website-on-website-usage',
                'email' => 'joe@yahoo.com',
                'usage' => ['for a website'],
            ],
            "api_key_request_require_website"
        );
        $lastVisitedURL = $this->cachedRequest->lastVisitedURL;
        $this->assertStringContainsString("required_fields=website_url", $lastVisitedURL);
    }

    /**
     * Tests that APIKey request should save a website if For a website is in usage
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldStoreWebsiteIfForWebsiteUsage(): void
    {
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'store-website-on-website-usage',
                'email' => 'joe@yahoo.com',
                'usage' => ['for a website'],
                'website_url' => 'http://www.pokemon.com',
            ],
            "api_key_request_store_website"
        );
        $statement = $this->db->query("SELECT `api_usage`, `website_url` from `md_api_keys` WHERE `name` = 'store-website-on-website-usage'");
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals('for a website', $data[0]['api_usage']);
        $this->assertEquals('http://www.pokemon.com', $data[0]['website_url']);
    }

    /**
     * Tests that APIKey requests with all fields should set status to 0 (ie. pending)
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldSetStatusToZeroIntially(): void
    {
        $usage = generateRandomKey(12);
        $content = $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'status_should_be_zero', 'email' => 'joe@yahoo.com', 'usage' => [$usage]),
            "status_should_be_zero"
        );
        $statement = $this->db->query("SELECT status from `md_api_keys` WHERE `name` = 'status_should_be_zero'");
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(0, $data[0]['status']);
    }

    /**
     * Tests that APIKey requests with all fields should set an authorize token for the url
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldCreateAnAuthorizeToken(): void
    {
        $usage = generateRandomKey(12);
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'should_set_authorize_token', 'email' => 'joe@gmail.com', 'usage' => [$usage]),
            "should_set_authorize_token"
        );
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE `name` = 'should_set_authorize_token'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertNotNull($data[0]['authorize_token']);
        $this->assertNotEmpty($data[0]['authorize_token']);
    }

    /**
     * Tests that APIKey get_my_api_key sets the key to active, and removes the authorize_token
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testGetMyAPIKeySetsProperFields(): void
    {
        $usage = generateRandomKey(12);
        $expectedStatus = 1;
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'i_should_become_active', 'email' => 'joe@gmail.com', 'usage' => [$usage]),
            "i_should_become_active"
        );
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE `api_usage` = '" . $usage . "'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->cachedRequest->get(
            $this->siteURL . "/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_become_active_authorize"
        );
        $statement = $this->db->query("SELECT * from `md_api_keys` WHERE `api_usage` = '" . $usage . "'");
        $actualData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedStatus, $actualData[0]['status']);
    }

    /**
     * Tests that APIKey get_my_api_key should not set a suspended status to active
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testGetMyAPIKeyShouldNotChangeKeysThatWereSuspended(): void
    {
        $usage = generateRandomKey(12);
        $expectedStatus = 2;
        $this->cachedRequest->post(
            $this->siteURL . "/api_keys/new",
            array('name' => 'i_should_stay_suspended', 'email' => 'joe@gmail.com', 'usage' => [$usage]),
            "i_should_stay_suspended"
        );
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE  `api_usage` = '" . $usage . "'");
        $statement = $this->db->query(
            "SELECT authorize_token from `md_api_keys` WHERE  `api_usage` = '" . $usage . "'"
        );
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->cachedRequest->get(
            $this->siteURL . "/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_stay_suspended_authorize"
        );
        $statement = $this->db->query("SELECT * from `md_api_keys` WHERE `api_usage` = '" . $usage . "'");
        $actualData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($expectedStatus, $actualData[0]['status']);
    }
}
