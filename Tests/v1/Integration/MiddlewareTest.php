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

use Doctrine\DBAL\Connection;
use Tests\Support\GuzzleHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * The class for testing middleware integration
 *
 * @author Johnathan Pulos
 */
class MiddlewareTest extends TestCase
{
    /**
     * The HTTP Client Object
     *
     * @var GuzzleHttpClient
     */
    public GuzzleHttpClient $httpClient;
    /**
     * The PDO database connection object
     *
     * @var Connection
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
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private string $APIKey = '';
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
        $this->APIKey = createApiKey();
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
        deleteApiKey($this->APIKey);
    }

    public function testItShouldRecordTheLastRequestDateOnEveryRequest(): void
    {
        $this->db->executeStatement(
            "UPDATE `md_api_keys` SET last_request = '2012-10-21 10:05:00' WHERE  `api_key` = :api_key",
            ['api_key' => $this->APIKey]
        );
        $response = $this->httpClient->get(
            $this->siteURL . "/" . $this->APIVersion . "/continents/asi.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->httpClient->responseCode);
        $data = $this->db->fetchAssociative(
            "SELECT * FROM `md_api_keys` WHERE  `api_key` = :api_key", 
            ['api_key' => $this->APIKey]
        );
        $this->assertFalse(empty($data['last_request']));
        $this->assertNotEquals('2012-10-21 10:05:00', $data['last_request']);
    }
}
