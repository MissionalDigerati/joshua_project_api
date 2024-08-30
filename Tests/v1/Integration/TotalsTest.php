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

class TotalsTest extends TestCase
{
    private $cachedRequest;
    private $db;
    private $APIKey = '';
    private $APIVersion;
    private $siteURL;

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
        $this->APIKey = createApiKey();
    }

    public function tearDown(): void
    {
        $this->cachedRequest->clearCache();
        deleteApiKey($this->APIKey);
    }

    public function testIndexShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            [],
            "totals_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('You are missing your API key.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldRefuseAccessWithoutActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '{$this->APIKey}'");
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            ['api_key' => $this->APIKey],
            "totals_non_active_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '{$this->APIKey}'");
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            ['api_key' => $this->APIKey],
            "totals_suspended_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            ['api_key' => 'BADKEY'],
            "totals_bad_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldReturnARegionInJSON(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            ['api_key' => $this->APIKey],
            "totals_show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testIndexShouldReturnARegionInXML(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.xml",
            ['api_key' => $this->APIKey],
            "totals_show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testIndexShouldReturnResults(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            ['api_key' => $this->APIKey],
            "totals_show_returns_results"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEmpty($decoded);
        $this->assertEquals(34, count($decoded));
        $this->assertArrayHasKey('id', $decoded[0]);
        $this->assertNotEmpty($decoded[0]['id']);
        $this->assertArrayHasKey('Value', $decoded[0]);
        $this->assertNotEmpty($decoded[0]['Value']);
        $this->assertArrayHasKey('RoundPrecision', $decoded[0]);
    }

    public function testShowShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            [],
            "totals_show_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('You are missing your API key.', $decoded['api']['error']['details']);
    }

    public function testShowShouldRefuseAccessWithoutActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '{$this->APIKey}'");
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            ['api_key' => $this->APIKey],
            "totals_show_non_active_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '{$this->APIKey}'");
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            ['api_key' => $this->APIKey],
            "totals_show_suspended_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            ['api_key' => 'BADKEY'],
            "totals_show_bad_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldReturnARegionInJSON(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            ['api_key' => $this->APIKey],
            "totals_show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testShowShouldReturnARegionInXML(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.xml",
            ['api_key' => $this->APIKey],
            "totals_show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testShowShouldReturnResults(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals/CntContinents.json",
            ['api_key' => $this->APIKey],
            "totals_show_returns_results"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertNotEmpty($decoded);
        $this->assertEquals(1, count($decoded));
        $this->assertArrayHasKey('id', $decoded[0]);
        $this->assertEquals('CntContinents', $decoded[0]['id']);
        $this->assertArrayHasKey('Value', $decoded[0]);
        $this->assertEquals(7, $decoded[0]['Value']);
        $this->assertArrayHasKey('RoundPrecision', $decoded[0]);
        $this->assertEquals(0, $decoded[0]['RoundPrecision']);
    }
}
