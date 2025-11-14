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

use Tests\Support\GuzzleHttpClient;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

/**
 * The class for testing integration of the API Keys
 *
 * @author Johnathan Pulos
 */
class APIKeysTest extends TestCase
{
    /**
     * The HTTP Client Object
     *
     * @var GuzzleHttpClient
     * @access public
     */
    public GuzzleHttpClient $httpClient;
    /**
     * The PDO database connection object
     *
     * @var Connection
     * @access private
     */
    private Connection $db;
    /**
     * The current API version number
     *
     * @var string
     * @access private
     **/
    private string $APIVersion;
    /**
     * The URL for the testing server
     *
     * @var string
     * @access private
     **/
    private string $siteURL;

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
        $this->httpClient = new GuzzleHttpClient();
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
        $this->httpClient->clearCache();
        $this->db->executeStatement("DELETE FROM `md_api_keys`");
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => '',
                'email' => '',
                'usage' => [],
                'terms_of_use' => 'true',
            ],
            "api_keys_required_fields"
        );
        $actualURL = $this->httpClient->lastVisitedURL;
        // URL decode to handle encoded characters like %7C (pipe)
        $decodedURL = urldecode($actualURL);
        $this->assertStringContainsString('required_fields=name|email|usage', $decodedURL);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => '',
                'email' => 'joe@yahoo.com',
                'usage' => ['testing'],
                'terms_of_use' => 'true',
            ),
            "api_keys_required_fields"
        );
        $actualURL = $this->httpClient->lastVisitedURL;
        $this->assertStringContainsString('required_fields=name&email=joe%40yahoo.com&usage=testing', $actualURL);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'joe',
                'email' => '',
                'usage' => ['testing'],
                'terms_of_use' => 'true',
            ),
            "api_keys_required_fields"
        );
        $actualURL = $this->httpClient->lastVisitedURL;
        $this->assertStringContainsString('required_fields=email&name=joe&usage=testing', $actualURL);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'joe',
                'email' => 'joe@yahoo.com',
                'usage' => [],
                'terms_of_use' => 'true',
            ),
            "api_keys_required_fields"
        );
        $actualURL = $this->httpClient->lastVisitedURL;
        $this->assertStringContainsString('required_fields=usage&name=joe&email=joe%40yahoo.com', $actualURL);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => $name,
                'email' => 'joe@yahoo.com',
                'usage' => ['api development'],
                'terms_of_use' => 'true',
            ),
            "api_keys_required_fields"
        );
        $lastVisitedURL = $this->httpClient->lastVisitedURL;
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => $name,
                'email' => 'joe@yahoo.com',
                'usage' => $usage,
                'terms_of_use' => 'true',
            ),
            "api_key_request_correct_usage"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT api_usage from `md_api_keys` WHERE `name` = :name", 
            ['name' => $name]
        );
        $this->assertEquals($expectedUsage, $data[0]['api_usage']);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'store-website-on-website-usage',
                'email' => 'joe@yahoo.com',
                'usage' => ['for a website'],
                'website_url' => 'http://www.pokemon.com',
                'terms_of_use' => 'true',
            ],
            "api_key_request_store_website"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT `api_usage`, `website_url` from `md_api_keys` WHERE `name` = 'store-website-on-website-usage'"
        );
        $this->assertEquals('for a website', $data[0]['api_usage']);
        $this->assertEquals('http://www.pokemon.com', $data[0]['website_url']);
    }

    /**
     * Tests that APIKey request should require other description if other is in usage
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldRequireOtherDescIfOtherUsage(): void
    {
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'require-other-purpose-on-other-usage',
                'email' => 'joe@yahoo.com',
                'usage' => ['other'],
                'other_purpose'=> '',
                'terms_of_use' => 'true',
            ],
            "require_other_purpose_on_other_usage"
        );
        $lastVisitedURL = $this->httpClient->lastVisitedURL;
        $this->assertStringContainsString("required_fields=other_purpose", $lastVisitedURL);
    }

    /**
     * Tests that APIKey request should save apple app store url if For a mobile app is in usage
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldStoreOtherPurposeIfForOtherUsage(): void
    {
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'store-other-purpose-on-other-usage',
                'email' => 'app-store@yahoo.com',
                'usage' => ['For a mobile app', 'other'],
                'apple_app_store' => 'http://www.apple.com/my-awesome-app',
                'other_purpose' => 'I am gathering stats for a specific survey.',
                'terms_of_use' => 'true',
            ],
            "api_key_request_store_other_purpose_on_other_usage"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT `api_usage`, `apple_app_store`, `google_play_store` from `md_api_keys` WHERE `name` = 'store-other-purpose-on-other-usage'"
        );
        $this->assertEquals('for a mobile app,other,i am gathering stats for a specific survey.', $data[0]['api_usage']);
        $this->assertEquals('http://www.apple.com/my-awesome-app', $data[0]['apple_app_store']);
    }

    /**
     * Tests that APIKey request should save google app store url if For a mobile app is in usage
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldStoreGoogleAppStoreIfForMobileAppUsage(): void
    {
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'store-google-store-on-mobile-app-usage',
                'email' => 'app-store@yahoo.com',
                'usage' => ['For a mobile app'],
                'google_play_store' => 'http://www.google.com/my-awesome-app',
                'terms_of_use' => 'true',
            ],
            "api_key_request_google_store_app_store"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT `api_usage`, `apple_app_store`, `google_play_store` from `md_api_keys` WHERE `name` = 'store-google-store-on-mobile-app-usage'"
        );
        $this->assertEquals('for a mobile app', $data[0]['api_usage']);
        $this->assertEquals('http://www.google.com/my-awesome-app', $data[0]['google_play_store']);
        $this->assertEquals(null, $data[0]['apple_app_store']);
    }

    /**
     * Tests that APIKey request should require terms of use to be checked
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testAPIKeyRequestShouldRequireTermsOfUseChecked(): void
    {
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            [
                'name' => 'require-other-purpose-on-other-usage',
                'email' => 'joe@yahoo.com',
                'usage' => ['other'],
                'other_purpose'=> '',
            ],
            "require_other_purpose_on_other_usage"
        );
        $lastVisitedURL = $this->httpClient->lastVisitedURL;
        $this->assertStringContainsString("required_fields=terms_of_use", $lastVisitedURL);
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
        $content = $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'status_should_be_zero',
                'email' => 'joe@yahoo.com',
                'usage' => [$usage],
                'terms_of_use' => 'true',
            ),
            "status_should_be_zero"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT status from `md_api_keys` WHERE `name` = 'status_should_be_zero'"
        );
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'should_set_authorize_token',
                'email' => 'joe@gmail.com',
                'usage' => [$usage],
                'terms_of_use' => 'true',
            ),
            "should_set_authorize_token"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT authorize_token from `md_api_keys` WHERE `name` = 'should_set_authorize_token'"
        );
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
        $usage = strtolower(generateRandomKey(12));
        $expectedStatus = 1;
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'i_should_become_active',
                'email' => 'joe@gmail.com',
                'usage' => [$usage],
                'terms_of_use' => 'true',
            ),
            "i_should_become_active"
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT authorize_token from `md_api_keys` WHERE `api_usage` = :usage",
            ['usage' => $usage]
        );
        $this->httpClient->get(
            $this->siteURL . "/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_become_active_authorize"
        );
        $results = $this->db->fetchAllAssociative(
            "SELECT * from `md_api_keys` WHERE `api_usage` = :usage", 
            ['usage' => $usage]
        );
        $this->assertEquals($expectedStatus, $results[0]['status']);
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
        $this->httpClient->post(
            $this->siteURL . "/api_keys/new",
            array(
                'name' => 'i_should_stay_suspended',
                'email' => 'joe@gmail.com',
                'usage' => [$usage],
                'terms_of_use' => 'true',
            ),
            "i_should_stay_suspended"
        );
        $this->db->executeStatement(
            "UPDATE `md_api_keys` SET status = 2 WHERE  `api_usage` = :usage", 
            ['usage' => $usage]
        );
        $data = $this->db->fetchAllAssociative(
            "SELECT authorize_token from `md_api_keys` WHERE  `api_usage` = :usage",
            ['usage' => $usage]
        );
        $this->httpClient->get(
            $this->siteURL . "/get_my_api_key",
            array('authorize_token' => $data[0]['authorize_token']),
            "i_should_stay_suspended_authorize"
        );
        $result = $this->db->fetchAllAssociative(
            "SELECT * from `md_api_keys` WHERE `api_usage` = :usage",
            ['usage' => $usage]
        );
        $this->assertEquals($expectedStatus, $result[0]['status']);
    }
}
