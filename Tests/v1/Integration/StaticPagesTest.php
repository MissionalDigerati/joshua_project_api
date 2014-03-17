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
 * The class for testing integration of the Static Pages
 *
 * @package default
 * @author Johnathan Pulos
 */
class StaticPagesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var object
     */
    public $cachedRequest;
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
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
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
        /**
         * Clear all the api_keys generated by the test
         *
         * @author Johnathan Pulos
         */
        $this->db->query("DELETE FROM `md_api_keys` WHERE `api_usage` = 'testing'");
    }
    /**
     * Users should be able to access the Home Page
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldDisplayTheHomePage()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/",
            array(),
            "show_home"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
    }
    /**
     * Users should be able to get their API Key
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldAllowUsersToGetAnAPIKeyIfValidAuthorizationKey()
    {
        $authorizationToken = 'l543g3$4';
        $expectedAPIKey = 'AKey$43';
        $this->db->query(
            "INSERT INTO md_api_keys (api_usage, api_key, authorize_token, status, created) VALUES ('testing', '" . $expectedAPIKey .
            "', '" . $authorizationToken . "', 0, NOW())"
        );
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => $authorizationToken),
            "get_my_api_key"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEquals(false, strpos(strtolower($response), 'api key has been activated'));
    }
    /**
     * Users should see warning if API Key is already active
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldTellUsersThatAPIKeyAlreadyUpdated()
    {
        $authorizationToken = 'l543g3$4Ac';
        $expectedAPIKey = 'AKey$43Ac';
        $this->db->query(
            "INSERT INTO md_api_keys (api_usage, api_key, authorize_token, status, created) VALUES ('testing', '" . $expectedAPIKey .
            "', '" . $authorizationToken . "', 1, NOW())"
        );
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => $authorizationToken),
            "get_my_api_key_active_key"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEquals(false, strpos(strtolower($response), 'api key was already activated'));
    }
    /**
     * Users should see warning if API Key has been suspended
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldTellUsersThatAPIKeyIsSuspended()
    {
        $authorizationToken = 'l543g3$4Ac';
        $expectedAPIKey = 'AKey$43Ac';
        $this->db->query(
            "INSERT INTO md_api_keys (api_usage, api_key, authorize_token, status, created) VALUES ('testing', '" . $expectedAPIKey .
            "', '" . $authorizationToken . "', 2, NOW())"
        );
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => $authorizationToken),
            "get_my_api_key_suspended_key"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEquals(false, strpos(strtolower($response), 'api key was suspended'));
    }
    /**
     * Users should see error if the Authorization Token is Missing
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldTellUsersWarnWhenTheAuthorizationTokenIsMissing()
    {
        $authorizationToken = 'l543g3$4Ac';
        $expectedAPIKey = 'AKey$43Ac';
        $this->db->query(
            "INSERT INTO md_api_keys (api_usage, api_key, authorize_token, status, created) VALUES ('testing', '" . $expectedAPIKey .
            "', '" . $authorizationToken . "', 2, NOW())"
        );
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/get_my_api_key",
            array('authorize_token' => ''),
            "get_my_api_key_missing_token"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEquals(false, strpos(strtolower($response), 'unable to locate your api key'));
    }
    /**
     * Users should be able to access the Resend Activation URLS page
     *
     * @return void
     * @author Johnathan Pulos
     */
    public function testWebsiteShouldDisplayTheResendActivationLinkPage()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/resend_activation_links",
            array(),
            "show_resend_activation_links"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
    }
}
